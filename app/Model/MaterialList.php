<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MaterialList extends Model
{
    public function material()
    {
        return $this->belongsTo("App\Model\Material", "material_id", "id");
    }
}
