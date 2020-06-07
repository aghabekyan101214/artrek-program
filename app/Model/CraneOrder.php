<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CraneOrder extends Model
{
    public function paidList()
    {
        return $this->hasMany("App\Model\PaidOrder", "crane_order_id", "id")->orderBy("id");
    }

    public function client()
    {
        return $this->belongsTo("App\Model\Client", "client_id", "id");
    }

    public function driver()
    {
        return $this->belongsTo("App\Model\Driver", "driver_id", "id");
    }
}
