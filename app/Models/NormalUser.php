<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class NormalUser extends Authenticatable
{
    use Notifiable;

    protected $table = 'normal_users';

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function chats()
    {
        return $this->hasMany(Chat::class, 'user_id');
    }
}
