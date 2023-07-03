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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('img')->nullable();
            $table->text('short_text')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('visit')->default(0);
            $table->integer('sale_count')->default(0);
            $table->float('rating')->default(0);
            $table->tinyInteger('suggestion')->default(0);
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
        Schema::dropIfExists('products');
    }
};
