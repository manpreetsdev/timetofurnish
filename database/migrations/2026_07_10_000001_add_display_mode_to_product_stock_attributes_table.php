<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('product_stock_attributes') && !Schema::hasColumn('product_stock_attributes', 'display_mode')) {
            Schema::table('product_stock_attributes', function (Blueprint $table) {
                $table->string('display_mode')->nullable()->after('value_sort_order');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('product_stock_attributes') && Schema::hasColumn('product_stock_attributes', 'display_mode')) {
            Schema::table('product_stock_attributes', function (Blueprint $table) {
                $table->dropColumn('display_mode');
            });
        }
    }
};
