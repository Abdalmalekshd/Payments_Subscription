<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('plan_id')->unsigned();
            $table->string('stripe_price_id'); // Stripe Price ID for daily
            $table->integer('price');    // Price in cents
            $table->enum('plan_type', ['day', 'week', 'month', 'year']);    // Price in cents
            $table->integer('discount')->nullable();
            $table->date('discount_limit')->nullable();
            $table->enum('discount_type', ['percentage', 'fixed','null'])->default('null')->nullable();
            $table->timestamps();
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_prices');
    }
}
