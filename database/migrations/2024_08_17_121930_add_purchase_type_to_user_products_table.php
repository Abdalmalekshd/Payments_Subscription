<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPurchaseTypeToUserProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_products', function (Blueprint $table) {
            $table->string('purchase_type')->nullable()->after('status');
            $table->timestamp('subscription_start_date')->nullable()->after('purchase_type');
            $table->timestamp('subscription_end_date')->nullable()->after('subscription_start_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_products', function (Blueprint $table) {
            //
        });
    }
}