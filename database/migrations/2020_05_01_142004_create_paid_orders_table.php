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

            $table->unsignedBigInteger("order_id");
            $table->foreign("order_id")->references("id")->on("orders")->onDelete("cascade");
            $table->decimal("price", 8, 1);
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
