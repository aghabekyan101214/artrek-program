<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeForeignInDriverSalaries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('driver_salaries', function (Blueprint $table) {
            $table->renameColumn("paid_order_id", "crane_order_id");
            $table->foreign("crane_order_id")->references("id")->on("crane_orders")->onDelete("cascade")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('driver_salaries', function (Blueprint $table) {
            //
        });
    }
}
