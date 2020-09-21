<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmployeeSalaryIdToPaidOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paid_orders', function (Blueprint $table) {
            $table->unsignedBigInteger("employee_salary_id")->nullable();
            $table->foreign("employee_salary_id")->references("id")->on('employee_salaries')->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paid_orders', function (Blueprint $table) {
            //
        });
    }
}
