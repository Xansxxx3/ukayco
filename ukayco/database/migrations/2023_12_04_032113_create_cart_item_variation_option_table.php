<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemVariationOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_item_variation_option', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shopping_cart_item_id');
            $table->unsignedInteger('variation_option_id');
            $table->timestamps();

            $table->foreign('shopping_cart_item_id')->references('id')->on('shopping_cart_items')->onDelete('cascade');
            $table->foreign('variation_option_id')->references('id')->on('variation_options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_item_variation_option');
    }
}
