<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderSpending extends Model
{

    public function paidList()
    {
        return $this->hasMany(OrderSpendingList::class, 'spending_order_id', 'id');
    }
}
