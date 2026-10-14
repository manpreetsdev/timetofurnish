<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->boolean('show_badge_on_product')->default(1);
            $table->boolean('show_description_on_product')->default(1);
        });
    }

    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn(['show_badge_on_product', 'show_description_on_product']);
        });
    }
};
