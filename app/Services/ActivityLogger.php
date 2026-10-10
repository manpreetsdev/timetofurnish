<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ActivityLogger
{
    protected array $ignoredAttributes = [
        'password',
        'remember_token',
        'verification_code',
        'email_verified_at',
        'updated_at',
        'created_at',
        'deleted_at',
        // Counters / housekeeping fields that change on ordinary page views
        'viewed',
        'views',
        'num_of_views',
        'last_login',
        'last_login_at',
        'last_seen',
        'device_token',
    ];

    // Tables that are never worth an audit entry (browsing side-effects and child rows
    // that are rewritten in bulk whenever their parent is saved)
    protected array $ignoredModels = [
        \App\Models\Cart::class,
        \App\Models\CartProduct::class,
        \App\Models\Search::class,
        \App\Models\Wishlist::class,
        \App\Models\FirebaseNotification::class,
        \App\Models\PasswordReset::class,
        \App\Models\ProductKeyword::class,
        \App\Models\ProductStock::class,
        \App\Models\ProductStockAttribute::class,
        \App\Models\ProductTax::class,
        \App\Models\ProductCategory::class,
        \App\Models\AttributeCategory::class,
        \App\Models\CouponUsage::class,
        \App\Models\AffiliateStats::class,
        \Illuminate\Notifications\DatabaseNotification::class,
    ];

    // Decides whether a model change is a "required" activity:
    //  - file uploads / upload deletions by any signed-in user
    //  - new user accounts (sign ups)
    //  - any create / update / delete / restore done by an admin or staff member
    // Everything else (guest & customer browsing, carts, searches, counters…) is not logged.
    protected function shouldLogModelEvent(string $action, Model $model): bool
    {
        if ($model instanceof ActivityLog) {
            return false;
        }

        foreach ($this->ignoredModels as $ignored) {
            if ($model instanceof $ignored) {
                return false;
            }
        }

        $actor = Auth::user();
        $isBackOffice = $actor && in_array($actor->user_type, ['admin', 'staff'], true);

        if ($model instanceof \App\Models\Upload) {
            return $isBackOffice || ($actor && in_array($action, ['created', 'deleted'], true));
        }

        if ($model instanceof \App\Models\User && $action === 'created') {
            return true;
        }

        return $isBackOffice;
    }

    public function log(string $action, $subject = null, array $properties = [], ?string $description = null): void
    {
        if (app()->runningInConsole() && ! app()->runningUnitTests()) {
            return;
        }

        $now = now();
        $user = Auth::user();
        $userId = null;
        if ($user) {
            if (DB::table('users')->where('id', $user->id)->exists()) {
                $userId = $user->id;
            }
        }

        try {
            DB::table((new ActivityLog())->getTable())->insert([
                'user_id' => $userId,
                'action' => $action,
                'subject_type' => $subject instanceof Model ? get_class($subject) : ($properties['subject_type'] ?? null),
                'subject_id' => $subject instanceof Model ? $subject->getKey() : ($properties['subject_id'] ?? null),
                'description' => $description ?: $this->buildDescription($action, $subject),
                'properties' => !empty($properties) ? json_encode($properties, JSON_UNESCAPED_SLASHES) : null,
                'url' => request() ? request()->fullUrl() : null,
                'ip_address' => request() ? request()->ip() : null,
                'user_agent' => request() ? Str::limit((string) request()->userAgent(), 2000, '') : null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } catch (\Throwable $e) {
            logger()->error('ActivityLogger failed: ' . $e->getMessage());
        }

        $this->pruneOldLogsOncePerDay();
    }

    // Keeps the table at one month of data even when no cron/scheduler is running
    protected function pruneOldLogsOncePerDay(): void
    {
        try {
            if (Cache::add('activity_logs_pruned_today', 1, now()->addDay())) {
                ActivityLog::pruneOld();
            }
        } catch (\Throwable $e) {
            logger()->error('ActivityLogger prune failed: ' . $e->getMessage());
        }
    }

    public function logModelEvent(string $action, Model $model): void
    {
        if (! $this->shouldLogModelEvent($action, $model)) {
            return;
        }

        // A user account created by anyone other than admin/staff is a sign up
        $actor = Auth::user();
        if ($model instanceof \App\Models\User && $action === 'created'
            && ! ($actor && in_array($actor->user_type, ['admin', 'staff'], true))) {
            $this->log('signup', $model, [
                'attributes' => $this->sanitizeAttributes(Arr::only($model->getAttributes(), ['name', 'email', 'phone', 'user_type'])),
            ], 'New ' . ($model->user_type ?: 'user') . ' signed up');

            return;
        }

        $properties = [];
        $attributes = $this->sanitizeAttributes($model->getAttributes());
        $changes = $this->sanitizeAttributes($model->getChanges());

        if ($action === 'created') {
            $properties['attributes'] = $attributes;
        } elseif ($action === 'updated') {
            $original = $this->sanitizeAttributes($model->getOriginal());
            $diff = [];

            foreach ($changes as $field => $newValue) {
                if ($field === 'updated_at') {
                    continue;
                }

                $diff[$field] = [
                    'old' => $original[$field] ?? null,
                    'new' => $newValue,
                ];
            }

            if (empty($diff)) {
                return;
            }

            $properties['changes'] = $diff;
        } elseif ($action === 'deleted' || $action === 'restored') {
            $properties['attributes'] = $attributes;
        }

        $this->log($action, $model, $properties);
    }

    protected function buildDescription(string $action, $subject = null): string
    {
        $subjectName = $subject instanceof Model ? class_basename($subject) : 'record';
        $prettySubject = Str::of($subjectName)->snake()->replace('_', ' ')->title();

        return match ($action) {
            'created' => 'Created ' . $prettySubject,
            'updated' => 'Updated ' . $prettySubject,
            'deleted' => 'Deleted ' . $prettySubject,
            'restored' => 'Restored ' . $prettySubject,
            'signup' => 'New user signed up',
            'login' => 'User logged in',
            'logout' => 'User logged out',
            'failed_login' => 'Login attempt failed',
            default => Str::of($action)->replace('_', ' ')->title(),
        };
    }

    protected function sanitizeAttributes(array $attributes): array
    {
        return Arr::except($attributes, $this->ignoredAttributes);
    }
}
