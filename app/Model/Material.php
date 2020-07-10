<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
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

    public function selfPrice()
    {
        return $this->hasOne(MaterialList::class, "material_id", "id")->latest();
    }
}
