<?php

namespace App\Models;

use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Cart extends Model
{
    /**
     * How long an add-to-cart holds stock for the customer (inventory hold).
     */
    public const RESERVATION_MINUTES = 60;

    /** Cached: has the reserved_until migration been run yet? */
    protected static ?bool $reservationColumn = null;

    public static function reservationEnabled(): bool
    {
        if (static::$reservationColumn === null) {
            try {
                static::$reservationColumn = Schema::hasColumn('carts', 'reserved_until');
            } catch (\Throwable $e) {
                static::$reservationColumn = false;
            }
        }
        return static::$reservationColumn;
    }

    protected $guarded = [];
    protected $fillable = ['address_id','price','tax', 'addons','shipping_cost','discount','product_referral_code','coupon_code','coupon_applied','quantity','user_id','temp_user_id','owner_id','product_id','variation','reserved_until'];

	 protected $casts = [
        'addons' => 'array',
        'reserved_until' => 'datetime',
    ];

    protected static function booted(): void
    {
        // Every ordinary "get my cart" query only returns lines whose
        // reservation is still active (or that were never reserved). Expired
        // lines stay in the table but are hidden from the active cart, totals,
        // checkout and stock maths. Read them with
        // ->withoutGlobalScope('active_reservation') or the scopes below.
        static::addGlobalScope('active_reservation', function (Builder $builder) {
            if (! static::reservationEnabled()) {
                return;
            }
            $builder->where(function (Builder $q) {
                $q->whereNull('reserved_until')
                  ->orWhere('reserved_until', '>', now());
            });
        });
    }

    /** Only cart lines whose reservation has lapsed ("Recently in cart"). */
    public function scopeExpiredReservations(Builder $query): Builder
    {
        $query->withoutGlobalScope('active_reservation');
        if (! static::reservationEnabled()) {
            return $query->whereRaw('1 = 0');
        }
        return $query->whereNotNull('reserved_until')
            ->where('reserved_until', '<=', now());
    }

    /** Include expired lines as well as active ones. */
    public function scopeWithExpiredReservations(Builder $query): Builder
    {
        return $query->withoutGlobalScope('active_reservation');
    }

    public function getReservationExpiredAttribute(): bool
    {
        return $this->reserved_until !== null && $this->reserved_until->isPast();
    }

    public function getReservationSecondsLeftAttribute(): int
    {
        if ($this->reserved_until === null) {
            return 0;
        }
        return max(0, now()->diffInSeconds($this->reserved_until, false));
    }

    /**
     * Units of a variant currently reserved by OTHER shoppers (active holds).
     * Subtract this from real stock to get the quantity a given viewer may buy.
     */
    public static function reservedQuantityByOthers($productId, $variation, $userId = null, $tempUserId = null): int
    {
        if (! static::reservationEnabled()) {
            return 0;
        }

        $userId = $userId ?: auth()->id();
        $tempUserId = $tempUserId ?: session()->get('temp_user_id');

        $query = static::withoutGlobalScope('active_reservation')
            ->where('product_id', $productId)
            ->where('variation', (string) $variation)
            ->whereNotNull('reserved_until')
            ->where('reserved_until', '>', now());

        if ($userId) {
            $query->where(function (Builder $q) use ($userId) {
                $q->whereNull('user_id')->orWhere('user_id', '!=', $userId);
            });
        } elseif ($tempUserId) {
            $query->where(function (Builder $q) use ($tempUserId) {
                $q->whereNull('temp_user_id')->orWhere('temp_user_id', '!=', $tempUserId);
            });
        }

        return (int) $query->sum('quantity');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function shop()
    {
        return $this->hasOne(Shop::class, 'user_id', 'owner_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
