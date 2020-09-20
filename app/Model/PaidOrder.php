<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PaidOrder extends Model
{
    protected $guarded = [];

    const CASH = 0;
    const TRANSFER = 1;


}
