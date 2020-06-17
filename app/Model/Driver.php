<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    const PERCENTAGE = 25;
    const PAID = 1;
    const NOT_PAID = 0;

    public function salary()
    {
        return $this->hasMany(DriverSalary::class, "driver_id", "id");
    }

    public function paidSalary()
    {
        return $this->hasMany(PaidOrder::class, "driver_id", "id");
    }

}
