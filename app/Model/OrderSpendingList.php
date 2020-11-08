<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderSpendingList extends Model
{
    public function paidOrder()
    {
        return $this->belongsTo(PaidOrder::class, 'paid_order_id', 'id');
    }
}
