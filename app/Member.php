<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    use Notifiable;

    protected $table = 'member';

    protected $fillable = [
        'username', 'password'
    ];
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * @return array
     * 前台认证规则
     */
    public function getAuthPassword()
    {
        return ['password' => $this->attributes['password'], 'salt' => $this->attributes['salt']];
    }

    /**
     * @return array
     * api密码验证
     */
    public function getApiPassword()
    {
        return ['password' => $this->attributes['password'], 'salt' => $this->attributes['salt']];
    }
}
