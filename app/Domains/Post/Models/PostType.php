<?php

namespace App\Domains\Post\Models;

use App\Domains\Auth\Models\User;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class PostType extends Model implements Sortable
{
    use HasUuids, SoftDeletes, Prunable, HasSlug,LogsActivity, SortableTrait;

    protected $fillable = [
        'name', 'slug', 'is_public', 'type', 'show_in_menu', 'user_id', 'is_category', 'is_tags', 'is_content', 'featured_image', 'featured'
    ];

    public $sortable = [
        'order_column_name' => 'sort',
        'sort_when_creating' => true,
    ];

    public static function loop()
    {
        $query = static::query()->where('show_in_menu', true)->ordered()->get();
        return $query;
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function post()
    {
        return Post::query()->where('type', $this->slug);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }
}
