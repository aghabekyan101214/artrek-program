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
            $table->foreign("order_id")->references("id")->on("orders")->onDelete("cascade");

            $table->unsignedBigInteger("crane_order_id")->nullable();
            $table->foreign("crane_order_id")->references("id")->on("crane_orders")->onDelete("cascade");

            $table->unsignedBigInteger("driver_id")->nullable();
            $table->foreign("driver_id")->references("id")->on("drivers")->onDelete("set null");

            $table->unsignedBigInteger("car_id")->nullable();
            $table->foreign("car_id")->references("id")->on("cars")->onDelete("set null");

            $table->unsignedBigInteger("spending_id")->nullable();
            $table->foreign("spending_id")->references("id")->on("spendings")->onDelete("set null");

            $table->decimal("price", 8, 1);
            $table->unsignedTinyInteger("at_driver")->nullable()->default(0);
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
