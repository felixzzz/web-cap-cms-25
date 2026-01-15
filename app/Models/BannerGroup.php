<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
