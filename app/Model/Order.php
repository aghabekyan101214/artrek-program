<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function client()
    {
        return $this->belongsTo("App\Model\Client", "client_id", "id");
    }

    public function orderList()
    {
        return $this->hasMany("App\Model\OrderList", "order_id", "id");
    }
}
