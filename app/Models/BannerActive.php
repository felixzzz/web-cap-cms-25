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
        'start_date',
        'end_date',
        'language',
        'is_hide_in_mobile',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_hide_in_mobile' => 'boolean',
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
