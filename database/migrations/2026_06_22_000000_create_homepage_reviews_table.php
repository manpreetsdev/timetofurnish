<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20)->default('text'); // 'text' (custom review) or 'image' (full image review)
            $table->string('image')->nullable(); // Upload file ID or path
            $table->string('name')->nullable();
            $table->text('review_text')->nullable();
            $table->integer('rating')->default(5);
            $table->date('review_date')->nullable();
            $table->string('category_tag')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_reviews');
    }
};
