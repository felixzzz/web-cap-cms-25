<?php

use App\Http\Controllers\Backend\HomeBannerController;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'home-banners',
    'as' => 'home-banners.',
], function () {
    Route::get('/', [HomeBannerController::class, 'edit'])
        ->name('edit')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Home Banner Configuration'), route('admin.home-banners.edit'));
        });

    Route::post('/', [HomeBannerController::class, 'update'])->name('update');
});
