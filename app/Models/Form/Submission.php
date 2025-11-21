<?php

namespace App\Models\Form;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Submission extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    protected static $recordEvents = ['deleted'];

    protected $fillable = [
        'form_id', 'fields'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function getFieldsAttribute($value)
    {
        return json_decode($value);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

}
