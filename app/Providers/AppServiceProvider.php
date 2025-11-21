<?php

namespace App\Providers;

use App\Domains\Core\Models\SidebarMenu;
use App\Models\Option;
use Database\Seeders\SidebarMenuSeeder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Option::chargeConfig();
        Paginator::useBootstrap();

        if(in_array(config('app.env'), ['qa', 'staging', 'production'])) {
            URL::forceScheme('https');
        }


        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        if (Schema::hasTable('sidebar_menus')) {
            View::share('menus',SidebarMenu::with('children')
                ->whereNull('parent_id')
                ->where('is_active',true)
                ->orderBy('order')->get());
        }

    }
}
