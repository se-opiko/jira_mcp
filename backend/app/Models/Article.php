<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'excerpt',
        'content',
        'thumbnail',
        'category',
        'tags',
        'read_time',
    ];

    protected $casts = [
        'tags' => 'array',
        'read_time' => 'integer',
    ];
}
