<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Option extends Model
{
    use HasFactory, LogsActivity;

    protected static $recordEvents = ['updated'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'option_name', 'option_value', 'is_autoload'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'is_autoload', 'id', 'created_at', 'updated_at'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public static function chargeConfig() : void
    {
        $settings = Cache::get('db_setting');

        config($settings);
    }

    public static function refreshCache()
    {
        Cache::forget('db_setting');

        Cache::rememberForever('db_setting', fn() => static::query()->get()->keyBy('option_name')
            ->transform(fn($setting) => $setting->option_value)->toArray());

        self::chargeConfig();
    }

    public function getOption($keys)
    {
        if (is_array($keys)) {
            $arr = [];
            return self::whereIn('option_name', $keys)->get()->keyBy('option_name') // key every setting by its name
                ->transform(function ($setting) {
                    return $setting->option_value;
                })->toArray();
        } else {

            $option = self::where('option_name', $keys)
                        ->first();

            return optional($option)->option_value;
        }
    }
}
