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
        Schema::create('factors', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('user_session')->nullable();
            $table->integer('address_id')->nullable();
            $table->string('price')->nullable();
            $table->string('shipping_cost')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->enum('pay_status', ['paid', 'unpaid'])->default('unpaid');
            $table->string('description', 512)->nullable();
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
        Schema::dropIfExists('factors');
    }
};
