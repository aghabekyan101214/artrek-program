<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaserListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laser_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("material_id");
            $table->foreign("material_id")->references("id")->on("materials")->onDelete("cascade");

            $table->unsignedBigInteger("order_id");
            $table->foreign("order_id")->references("id")->on("orders")->onDelete("cascade");

            $table->decimal("self_price", 8, 1);

            $table->decimal("quantity", 8, 1);
            $table->unsignedTinyInteger("type");
            $table->unsignedInteger("thickness")->nullable();
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
        Schema::dropIfExists('laser_lists');
    }
}
