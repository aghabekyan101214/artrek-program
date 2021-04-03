<?php

namespace App;

use App\Model\UserAllowedRoutes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    const SUPER_ADMIN = 1;
    const ADMIN = 2;

    const ROLES = [
        "superadmin" => self::SUPER_ADMIN,
        "admin" => self::ADMIN
    ];

    protected $guard = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function allowedRoutes()
    {
        return $this->hasMany(UserAllowedRoutes::class, 'user_id', 'id');
    }
}
