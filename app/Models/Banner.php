<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';

    protected $fillable = [
        'banner_group_id',
        'order',
        'title',
        'description',
        'image',
        'aspect_ratio',
        'video',
        'html',
        'cta_url',
        'cta_label',
        'cta_gtm',
    ];

    public function group()
    {
        return $this->belongsTo(BannerGroup::class, 'banner_group_id');
    }
}
