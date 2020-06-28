<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    public function driver()
    {
        return $this->hasOne(Driver::class, "car_id", "id");
    }
}
