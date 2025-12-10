<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'prompt',
        'title',
        'model',
        'input_tokens',
        'output_tokens',
        'cost',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
