<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
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
    ];

    public function log(string $action, $subject = null, array $properties = [], ?string $description = null): void
    {
        if (app()->runningInConsole() && ! app()->runningUnitTests()) {
            return;
        }

        $now = now();
        $user = Auth::user();

        DB::table((new ActivityLog())->getTable())->insert([
            'user_id' => $user ? $user->id : null,
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
    }

    public function logModelEvent(string $action, Model $model): void
    {
        if ($model instanceof ActivityLog) {
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
