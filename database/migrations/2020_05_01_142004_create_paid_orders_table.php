<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaidOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paid_orders', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger("order_id")->nullable();
            $table->foreign("order_id")->references("id")->on("orders")->onDelete("set null");

            $table->unsignedBigInteger("crane_order_id")->nullable();
            $table->foreign("crane_order_id")->references("id")->on("crane_orders")->onDelete("set null");

            $table->decimal("price", 8, 1);
            $table->unsignedTinyInteger("at_driver")->nullable();
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
        Schema::dropIfExists('paid_orders');
    }
}
