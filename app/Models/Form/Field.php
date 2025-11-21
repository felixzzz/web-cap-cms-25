<?php

namespace App\Models\Form;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Field extends Model implements Sortable
{
    use HasFactory, HasUuids, SortableTrait, HasSlug, LogsActivity;

    protected $fillable = [
        'name', 'label', 'slug', 'type', 'input', 'is_required', 'placeholder', 'options', 'form_id', 'class', 'sort'
    ];

    protected $hidden = [
        'id', 'form_id', 'deleted_at', 'created_at', 'updated_at'
    ];

    public $sortable = [
        'order_column_name' => 'sort',
        'sort_when_creating' => true,
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    /**
    * Get the options for generating the slug.
    */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('label')
            ->saveSlugsTo('name')
            ->usingSeparator('_');
    }

    public function buildSortQuery()
    {
        return static::query()->where('form_id', $this->form_id);
    }

    public function getOptionsAttribute($value)
    {
        if ($value != null) {
            return json_decode($value);
        }
        return [];
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
