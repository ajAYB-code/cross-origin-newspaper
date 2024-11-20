<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'article_id', // Foreign key
        'author',     // Author name
        'content',    // Comment content
    ];
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

}
