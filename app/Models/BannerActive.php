<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Post\Models\Post;

class BannerActive extends Model
{
    use HasFactory;

    protected $table = 'banner_active';

    protected $fillable = [
        'banner_group_id',
        'post_id',
        'location',
        'end_date',
    ];

    protected $casts = [
        'end_date' => 'datetime',
    ];

    public function bannerGroup()
    {
        return $this->belongsTo(BannerGroup::class, 'banner_group_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
