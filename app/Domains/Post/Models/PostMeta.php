<?php

namespace App\Domains\Post\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class PostMeta extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id',
        'key',
        'value',
        'type',
        'section',
    ];
}
