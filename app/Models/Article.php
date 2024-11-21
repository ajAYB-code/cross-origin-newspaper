<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Article extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'author',
        'category',
        'content',
        'published_at',
        'source',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

}
