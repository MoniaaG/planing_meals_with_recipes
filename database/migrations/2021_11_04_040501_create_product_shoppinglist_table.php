<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductShoppinglistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_shoppinglist', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shoppinglist_id');
            $table->foreign('shoppinglist_id')->references('id')->on('shoppinglists');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->decimal('quantity',8,2);
            $table->boolean('added_to_pantry')->default(false);
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
        Schema::dropIfExists('product_shoppinglist');
    }
}
