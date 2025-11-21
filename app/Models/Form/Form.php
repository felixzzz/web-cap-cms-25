<?php

namespace App\Models\Form;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Form extends Model
{
    use HasUuids, LogsActivity;

    protected $fillable = [
        'name', 'auto_reply', 'message', 'admin_email', 'subject'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }
    
    public function field()
    {
        return $this->hasMany(Field::class);
    }

    public function submission()
    {
        return $this->hasMany(Submission::class);
    }
}
