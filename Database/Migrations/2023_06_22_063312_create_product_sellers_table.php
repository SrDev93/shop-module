<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_sellers', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('seller_id');
            $table->integer('stock')->default(0);
            $table->bigInteger('price')->nullable();
            $table->tinyInteger('off')->nullable();
            $table->bigInteger('price_off')->nullable();
            $table->string('warranty')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('sale_count')->default(0);
            $table->string('amazing_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_sellers');
    }
};
