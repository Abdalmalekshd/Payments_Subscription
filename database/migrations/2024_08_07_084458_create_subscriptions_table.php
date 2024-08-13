<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->string('subscription_id'); // Stripe's subscription ID
            $table->string('stripe_price_id'); // Stripe's price ID
            $table->string('status'); // Subscription status (active, canceled, etc.)
            $table->integer('plan_id')->unsigned();
            $table->timestamp('current_period_start')->nullable(); // Start of the current billing period
            $table->timestamp('current_period_end')->nullable(); // End of the current billing period
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('subscriptions');
    }
}