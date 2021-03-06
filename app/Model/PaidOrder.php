<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PaidOrder extends Model
{

    use CreatorTrait;

    protected $guarded = [];

    const CASH = 0;
    const TRANSFER = 1;

    const DRIVER_SALARY_COLLECTED = 1;
    const DRIVER_SALARY_FIXED = 2;

    const MONTHS = [
        ['index' => 1, 'name' => 'Հունվար'],
        ['index' => 2, 'name' => 'Փետրվար'],
        ['index' => 3, 'name' => 'Մարտ'],
        ['index' => 4, 'name' => 'Ապրիլ'],
        ['index' => 5, 'name' => 'Մայիս'],
        ['index' => 6, 'name' => 'Հունիս'],
        ['index' => 7, 'name' => 'Հուլիս'],
        ['index' => 8, 'name' => 'Օգոստոս'],
        ['index' => 9, 'name' => 'Սեպտեմբեր'],
        ['index' => 10, 'name' => 'Հոկտեմբեր'],
        ['index' => 11, 'name' => 'Նոյեմբեր'],
        ['index' => 12, 'name' => 'Դեկտեմբեր'],
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

}
