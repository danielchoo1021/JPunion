<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transaction_id')->nullable();
            $table->string('product_image')->nullable();
            $table->string('product_id')->nullable();
            $table->string('sub_category')->nullable();
            $table->string('product_name')->nullable();
            $table->string('unit_price')->nullable();
            $table->string('quantity')->nullable();
            $table->string('total_amount')->nullable();
            $table->boolean('status')->unsigned()->default(true);
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
        Schema::dropIfExists('transactions');
    }
}
