<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Post\Models\Post;

class BannerGroup extends Model
{
    use HasFactory;

    protected $table = 'banner_groups';

    protected $fillable = [
        'title',
        'banners', // json
        'bulk_position',
    ];

    protected $casts = [
        'banners' => 'array',
    ];

    public function banners()
    {
        return $this->hasMany(Banner::class, 'banner_group_id');
    }

    public function activeBanners()
    {
        return $this->hasMany(BannerActive::class, 'banner_group_id');
    }

    public function posts()
    {
        return $this->hasManyThrough(Post::class, BannerActive::class, 'banner_group_id', 'id', 'id', 'post_id');
    }
}
