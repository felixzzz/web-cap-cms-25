<?php

namespace App\Domains\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class SidebarMenu extends Model
{
    public $incrementing = false;
    protected $fillable = [
        'id',
        'title',
        'parent_id',
        'url',
        'icon',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /*auto generate uuid*/
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Str::uuid()->toString();
        });
    }
    public function parent()
    {
        return $this->belongsTo(SidebarMenu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(SidebarMenu::class, 'parent_id')->orderBy('order');
    }

    /*belong to many*/
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'menu_permissions','menu_id','permission_id');
    }
}
