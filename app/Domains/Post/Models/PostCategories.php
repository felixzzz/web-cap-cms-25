<?php

namespace App\Domains\Post\Models;

use Illuminate\Database\Eloquent\Model;

class PostCategories extends Model
{
    protected $table = 'post_category';
    protected $fillable = [
        'post_id',
        'category_id',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
