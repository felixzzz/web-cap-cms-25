<?php

namespace App\Models\User;

use App\Traits\Uuid;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission implements Sortable
{
    use Uuid, SortableTrait;

    protected $fillable = [
        'id'
    ];


}
