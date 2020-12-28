<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    //
    use Notifiable;
    protected $table = 'admins';
    protected $fillable = [
        'name', 'password'
    ];
    protected $hidden = [
        'password', 'remember_token'
    ];
    public function getAuthPassword()
    {
        return ['password' => $this->attributes['password'], 'salt' => $this->attributes['salt']];
    }
}
