<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PaidOrder extends Model
{
    protected $guarded = [];

    const CASH = 0;
    const TRANSFER = 1;

    public function salary()
    {
        return $this->hasOne(DriverSalary::class, "paid_order_id", "id");
    }
}
