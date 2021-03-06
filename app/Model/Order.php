<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    use CreatorTrait;

    public function client()
    {
        return $this->belongsTo("App\Model\Client", "client_id", "id");
    }

    public function orderList()
    {
        return $this->hasMany("App\Model\OrderList", "order_id", "id");
    }

    public function paidList()
    {
        return $this->hasMany("App\Model\PaidOrder", "order_id", "id")->orderBy("id");
    }

    public function laserList()
    {
        return $this->hasMany("App\Model\LaserList", "order_id", "id");
    }

    public function spendings()
    {
        return $this->hasMany(OrderSpending::class, 'order_id', 'id');
    }
}
