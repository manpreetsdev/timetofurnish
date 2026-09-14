<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_keywords', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->string('keyword', 255);
            $table->unsignedInteger('monthly_volume')->nullable();
            $table->decimal('kd', 5, 2)->nullable();
            $table->enum('intent', ['informational', 'transactional', 'navigational', 'commercial'])->nullable();
            $table->boolean('is_primary')->default(false);
            $table->enum('status', ['pending', 'verified'])->default('pending');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->index('product_id');
            $table->index('keyword');
            $table->unique(['product_id', 'keyword']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_keywords');
    }
};
