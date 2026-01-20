<?php

namespace App\Domains\Post\Models;

use App\Domains\Auth\Models\User;
use App\Domains\PostCategory\Models\Category;
use App\Traits\HasMeta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;

class Post extends Model implements HasMedia
{
    use HasFactory,
        SoftDeletes,
        Prunable,
        HasSlug,
        HasTags,
        InteractsWithMedia,
        LogsActivity,
        HasMeta;

    // Post Type
    public const TYPE_PAGE = 'page';
    public const TYPE_ARTICLE = 'article';

    // Post Status
    public const STATUS_PUBLISH = 'publish';
    public const STATUS_DRAFT = 'draft';
    public const STATUS_REVIEW = 'review';
    public const STATUS_SCHEDULE = 'schedule';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sort',
        'type',
        'title',
        'slug',
        'site_url',
        'title_en',
        'slug_en',
        'excerpt',
        'status',
        'published_at',
        'featured',
        'user_id',
        'content',
        'meta_title',
        'meta_description',
        'meta_keyword',
        'share_count',
        'like_count',
        'view_count',
        'parent',
        'pages_dynamic',
        'template',
        'post_type',
        'alt_image',
        'alt_image_en'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'published_at'
    ];

    protected $appends = [
        'template'
    ];

    public function isAdmin(): bool
    {
        return true;
    }

    public function getTemplateAttribute()
    {
        if ($this->type === 'page') {
            return $this->getMeta('template');
        }
        return null;
    }

    public function getSlugOptions(): SlugOptions
    {
        $options = SlugOptions::create()->saveSlugsTo('slug');

        if ($this->type === 'blog') {
            $options->generateSlugsFrom('slug');
        } else {
            $options->generateSlugsFrom('title');
        }

        return $options;
    }

    public function featured_image()
    {
        $fimage = $this->getFirstMediaUrl('featured_image');
        if ($fimage != null) {
            return $fimage;
        }
        return asset('media/svg/avatars/blank.svg');
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('featured_image')
            ->singleFile();
    }

    public function posttype()
    {
        return $this->belongsTo(PostType::class);
    }

    public function children()
    {
        return $this->hasMany(Post::class, 'parent', 'id');
    }

    public function parent()
    {
        return $this->hasOne(Post::class, 'id', 'parent');
    }

    public function parent_data()
    {
        return $this->hasOne(Post::class, 'id', 'parent');
    }

    public function category()
    {
        return $this->belongsToMany(Category::class, 'post_category');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function meta()
    {
        return $this->hasMany(PostMeta::class);
    }
    public function meta_result()
    {
        return $this->hasMany(PostMeta::class);
    }

    public function activeBanners()
    {
        return $this->hasMany(\App\Models\BannerActive::class);
    }
}
