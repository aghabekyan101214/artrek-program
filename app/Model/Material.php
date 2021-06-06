<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{

    use CreatorTrait;

    const UNITS = [
        "Մ²",
        "Մետր",
        "Հատ"
    ];

    public function quantity()
    {
        return $this->hasMany("App\Model\MaterialList", "material_id", "id");
    }

    public function used()
    {
        return $this->hasMany("App\Model\OrderList", "material_id", "id");
    }

    public function usedLaser()
    {
        return $this->hasMany("App\Model\LaserList", "material_id", "id");
    }

    public function selfPrice()
    {
        return $this->hasOne(MaterialList::class, "material_id", "id")->latest();
    }
}
