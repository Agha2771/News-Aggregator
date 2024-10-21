<?php

namespace News\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\ArticleFactory;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'author',
        'category',
        'source',
        'published_at',
        'url'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function newFactory()
    {
        return ArticleFactory::new();
    }
}
