<?php

namespace App\Domains\PostCategory\Models;

use App\Domains\Auth\Models\User;
use App\Domains\PostCategory\Models\Traits\Attribute\PostCategoryAttribute;
use App\Domains\PostCategory\Models\Traits\Scope\PostCategoryScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class Category extends Model
{
    use HasFactory;
    use HasRoles;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'name',
        'name_en',
        'description',
        'description_en',
        'slug_en',
        'slug',
        'sort'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function posts()
    {
        return $this->belongsToMany('App\Domains\Post\Models\Post', 'post_category');
    }
    public function active_posts()
    {
        return $this->belongsToMany('App\Domains\Post\Models\Post', 'post_category')->where('posts.status', 'publish');
    }

}
