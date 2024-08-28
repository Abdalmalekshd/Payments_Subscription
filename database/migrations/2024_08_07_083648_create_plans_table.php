<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            // $table->string('daily_price_id')->nullable(); // Stripe Price ID for daily
            // $table->string('weekly_price_id')->nullable(); // Stripe Price ID for weekly
            // $table->string('monthly_price_id')->nullable(); // Stripe Price ID for monthly
            // $table->string('yearly_price_id')->nullable();  // Stripe Price ID for yearly
            // $table->integer('daily_price')->nullable();   // Price in cents
            // $table->integer('weekly_price')->nullable();   // Price in cents
            // $table->integer('monthly_price')->nullable();   // Price in cents
            // $table->integer('yearly_price')->nullable();    // Price in cents
            // $table->integer('discount')->nullable();
            // $table->string('discount_duration')->nullable();
            // $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage')->nullable();
            $table->text('plan_description')->nullable();
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
        Schema::dropIfExists('plans');
    }
}