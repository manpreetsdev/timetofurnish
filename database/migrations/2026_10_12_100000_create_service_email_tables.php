<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('service_email_templates', function (Blueprint $table) {
            $table->id();
            // null = general template, usable for any order
            $table->unsignedBigInteger('checkout_service_id')->nullable()->index();
            $table->string('name');
            $table->string('subject');
            $table->longText('body');
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });

        Schema::create('order_email_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable()->index();
            $table->unsignedBigInteger('checkout_service_id')->nullable();
            $table->unsignedBigInteger('service_email_template_id')->nullable();
            // service_email = sent by staff from an order, system = automatic site email
            $table->string('type', 30)->default('service_email')->index();
            $table->string('to_email');
            $table->string('cc')->nullable();
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();
            $table->string('subject');
            $table->longText('body')->nullable();
            $table->string('status', 20)->default('sent')->index();
            $table->text('error')->nullable();
            $table->unsignedBigInteger('sent_by')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_email_logs');
        Schema::dropIfExists('service_email_templates');
    }
};
