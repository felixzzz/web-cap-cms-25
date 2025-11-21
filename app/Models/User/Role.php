<?php

namespace App\Models\User;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;


class Role extends SpatieRole
{
    use Uuid;

    protected $fillable = [
        'id'
    ];
}
