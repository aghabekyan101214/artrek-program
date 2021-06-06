<?php

namespace App;

use App\Model\CreatorTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Employee extends Model
{
    use CreatorTrait;

    public function salaries()
    {
        return $this->hasMany(EmployeeSalary::class, 'employee_id', "id")->orderBy("id", "DESC");
    }
}
