<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReplacementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replacements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_recipe_id');
            $table->foreign('product_recipe_id')->references('id')->on('product_recipe');
            $table->unsignedBigInteger('owner_replacement_id');
            $table->foreign('owner_replacement_id')->references('id')->on('users');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');
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
        Schema::dropIfExists('replacements');
    }
}
