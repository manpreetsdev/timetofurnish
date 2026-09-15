<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_keywords', function (Blueprint $table) {
            $table->enum('type', ['primary', 'secondary', 'long_tail', 'material', 'colour', 'feature'])
                ->default('secondary')
                ->after('keyword');
        });

        // Backfill existing rows from the is_primary flag.
        \DB::table('product_keywords')->where('is_primary', true)->update(['type' => 'primary']);
    }

    public function down(): void
    {
        Schema::table('product_keywords', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
