<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('team_members')) {
            Schema::create('team_members', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('department')->nullable();
                $table->string('designation')->nullable();
                $table->string('email')->nullable();
                $table->text('bio')->nullable();
                $table->string('photo')->nullable();
                $table->integer('department_sort_order')->default(0);
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
