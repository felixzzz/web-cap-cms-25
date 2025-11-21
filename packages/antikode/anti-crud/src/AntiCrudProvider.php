<?php

namespace Antikode\AntiCrud;

use Illuminate\Support\ServiceProvider;

class AntiCrudProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/anti-crud.php' => config_path('anti-crud.php'),
            __DIR__ . '/resources/stubs' => base_path('resources/stub'),
        ]);
    }

    public function register()
    {
        $this->commands([
            \Antikode\AntiCrud\Console\Generator::class,
        ]);
    }
}
