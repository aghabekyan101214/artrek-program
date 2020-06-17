<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_salaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("paid_order_id")->nullable();
            $table->foreign("paid_order_id")->references("id")->on("paid_orders")->onDelete("cascade");

            $table->unsignedBigInteger("driver_id");
            $table->foreign("driver_id")->references("id")->on("drivers")->onDelete("cascade");

            $table->decimal("price");
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
        Schema::dropIfExists('driver_salaries');
    }
}
