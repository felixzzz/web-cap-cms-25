<?php

namespace Antikode\AntiCrud;

class ThePath
{
    public static function prefix()
    {
        return config('anti-crud.prefix_path');
    }

    public static function controllerPath()
    {
        return app_path('Http/Controllers/'.static::prefix());
    }

    public static function requestPath()
    {
        return app_path('Http/Requests/'.static::prefix());
    }

    public static function observerPath()
    {
        return app_path('Http/Observers/'.static::prefix());
    }

    public static function modelPath()
    {
        return app_path('Models/'.static::prefix());
    }
}
