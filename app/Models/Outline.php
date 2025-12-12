<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outline extends Model
{
    protected $fillable = [
        'user_id',
        'content_type',
        'topic',
        'brand_name',
        'keywords',
        'location',
        'model',
        'generated_outlines',
    ];

    protected $casts = [
        'generated_outlines' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(NormalUser::class, 'user_id');
    }
}
