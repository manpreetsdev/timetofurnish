<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            // the customer who owns the order (so their inbox is one indexed lookup)
            $table->unsignedBigInteger('customer_id')->nullable()->index();
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->string('sender_type', 20); // admin | customer
            $table->boolean('is_alert')->default(0); // admin message that needs the customer's attention
            $table->text('body')->nullable();
            $table->timestamp('read_by_customer_at')->nullable();
            $table->timestamp('read_by_admin_at')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'sender_type', 'read_by_customer_at'], 'order_messages_customer_unread');
            $table->index(['sender_type', 'read_by_admin_at'], 'order_messages_admin_unread');
        });

        Schema::create('order_message_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_message_id')->index();
            $table->unsignedBigInteger('order_id')->index();
            $table->string('path');
            $table->string('original_name');
            $table->string('mime', 100)->nullable();
            $table->string('kind', 20)->default('file'); // image | video | file
            $table->unsignedBigInteger('size')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_message_files');
        Schema::dropIfExists('order_messages');
    }
};
