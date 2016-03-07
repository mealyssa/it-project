<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Expense extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('Expense', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_number');
            $table->string('item_name');
            $table->float('price');
            $table->string('merchant_name');
            $table->dateTime('date_purchased');
            $table->string('place_purchased');
            $table->integer('receipt_id');

          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Expense');
    }
}
