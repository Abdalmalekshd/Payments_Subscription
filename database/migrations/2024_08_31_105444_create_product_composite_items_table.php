<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCompositeItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_composite_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('product_composite_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->integer('qty');
            $table->foreign('product_composite_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('item_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('product_composite_items');
    }
}
