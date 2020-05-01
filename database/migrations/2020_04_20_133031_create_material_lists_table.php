<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("material_id");
            $table->foreign("material_id")->references("id")->on("materials")->onDelete("cascade");
            $table->decimal("quantity", 8, 1);
            $table->decimal("self_price", 8, 1);
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
        Schema::dropIfExists('material_lists');
    }
}
