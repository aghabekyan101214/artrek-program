<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCraneOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crane_orders', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger("client_id");
            $table->foreign("client_id")->references("id")->on("clients")->onDelete("cascade");

            $table->unsignedBigInteger("driver_id");
            $table->foreign("driver_id")->references("id")->on("drivers")->onDelete("cascade");

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
        Schema::dropIfExists('crane_orders');
    }
}
