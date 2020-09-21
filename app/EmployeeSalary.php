<?php

namespace App;

use App\Model\PaidOrder;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalary extends Model
{
    protected $guarded = [];

    public function paidSalaries()
    {
        return $this->hasOne(PaidOrder::class, 'employee_salary_id', "id");
    }
}
