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
}