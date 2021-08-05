<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use CreatorTrait;

    const PERCENTAGE = 30;
    const PAID = 1;
    const NOT_PAID = 0;

    public function salary()
    {
        return $this->hasMany(DriverSalary::class, "driver_id", "id");
    }

    public function paidSalary()
    {
        return $this->hasMany(PaidOrder::class, "driver_id", "id")->orderBy("id", "DESC");
    }

    public function car()
    {
        return $this->belongsTo(Car::class, "car_id","id");
    }
}
