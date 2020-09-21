<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Employee extends Model
{
    public function salaries()
    {
        return $this->hasMany(EmployeeSalary::class, 'employee_id', "id")->orderBy("id", "DESC");
    }
}
