<?php
namespace App\Domains\Tag\Models;

use Spatie\Tags\Tag as SpatieTag;

class Tag extends SpatieTag
{
    protected $fillable = ['name', 'locale'];
}
