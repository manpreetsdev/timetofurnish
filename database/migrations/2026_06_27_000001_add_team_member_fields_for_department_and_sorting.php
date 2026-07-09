<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            if (! Schema::hasColumn('team_members', 'department')) {
                $table->string('department')->nullable()->after('name');
            }

            if (! Schema::hasColumn('team_members', 'designation')) {
                $table->string('designation')->nullable()->after('department');
            }

            if (! Schema::hasColumn('team_members', 'department_sort_order')) {
                $table->integer('department_sort_order')->default(0)->after('photo');
            }

            if (! Schema::hasColumn('team_members', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('department_sort_order');
            }
        });
    }

    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            if (Schema::hasColumn('team_members', 'sort_order')) {
                $table->dropColumn('sort_order');
            }

            if (Schema::hasColumn('team_members', 'department_sort_order')) {
                $table->dropColumn('department_sort_order');
            }

            if (Schema::hasColumn('team_members', 'designation')) {
                $table->dropColumn('designation');
            }

            if (Schema::hasColumn('team_members', 'department')) {
                $table->dropColumn('department');
            }
        });
    }
};
