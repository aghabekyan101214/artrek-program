<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LaserList extends Model
{
    const TYPES = ["Կտրում", "Փորագրում"];
    const ORDINAR = -1; //Սովորական (ոչ լազեր)
    const CUT = 70; // Cut Price
    const ENGRAVING = 200; // Engraving Price

    protected $guarded = [];
}
