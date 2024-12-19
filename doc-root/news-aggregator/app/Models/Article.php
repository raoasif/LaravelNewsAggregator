<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'source_api_id',
        'source_name',
        'title',
        'author', 
        'description',
        'content',
        'url',
        'url_to_image',
        'category',
        'section',
        'published_at'
    ];
}
