<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Spending extends Model
{
    public function spendings()
    {
        return $this->hasMany(PaidOrder::class, "spending_id", "id");
    }
}
