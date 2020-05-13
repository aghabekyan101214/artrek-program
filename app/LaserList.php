<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LaserList extends Model
{
    const TYPES = ["Կտրում", "Փորագրում"];
    const CUT = 70; // Cut Price
    const ENGRAVING = 200; // Engraving Price
}
