<?php


namespace App\Model;


use App\User;
use Illuminate\Support\Facades\Auth;

Trait CreatorTrait
{
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function save(array $options = [])
    {
        $this->created_by = Auth::user()->id;
        return parent::save($options);
    }
}
