<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'content',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(NormalUser::class, 'user_id');
    }
}
