<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderSpendingListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_spending_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('spending_order_id');
            $table->foreign('spending_order_id')->references('id')->on('order_spendings')->onDelete("cascade");
            $table->decimal("price", 14, 1);
            $table->unsignedBigInteger('paid_order_id');
            $table->foreign('paid_order_id')->references('id')->on('paid_orders')->onDelete("cascade");
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
        Schema::dropIfExists('order_spending_lists');
    }
}
