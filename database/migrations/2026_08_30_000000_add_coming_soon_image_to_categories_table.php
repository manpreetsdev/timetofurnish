<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('categories') && ! Schema::hasColumn('categories', 'coming_soon_image')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('coming_soon_image', 100)->nullable()->after('cover_image');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'coming_soon_image')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('coming_soon_image');
            });
        }
    }
};
