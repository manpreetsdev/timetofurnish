<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('carts') && ! Schema::hasColumn('carts', 'reserved_until')) {
            Schema::table('carts', function (Blueprint $table) {
                // When set and still in the future, this cart line holds stock
                // (an "inventory reservation"). When it is in the past the line
                // is treated as an expired reservation: kept for the customer's
                // "Recently in cart" list but no longer holding stock and not
                // purchasable.
                $table->timestamp('reserved_until')->nullable()->after('quantity');
                $table->index(['product_id', 'variation', 'reserved_until'], 'carts_reservation_idx');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('carts') && Schema::hasColumn('carts', 'reserved_until')) {
            Schema::table('carts', function (Blueprint $table) {
                $table->dropIndex('carts_reservation_idx');
                $table->dropColumn('reserved_until');
            });
        }
    }
};
