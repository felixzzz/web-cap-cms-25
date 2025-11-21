<?php

namespace App\Models\Extra;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    use HasFactory, Uuid;

    protected $guarded = [];
    public $timestamps = false;
    protected $table = 'metas';
}
