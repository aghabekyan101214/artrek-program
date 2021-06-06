<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{

    use CreatorTrait;

    public function driver()
    {
        return $this->hasOne(Driver::class, "car_id", "id");
    }
}
