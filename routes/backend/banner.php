<?php

use App\Http\Controllers\Backend\BannerGroupController;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'banner',
    'as' => 'banner.',
], function () {
    Route::get('/', [BannerGroupController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Banner Groups'), route('admin.banner.index'));
        });

    Route::get('/create', [BannerGroupController::class, 'create'])
        ->name('create')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.banner.index')
                ->push(__('Create Banner'), route('admin.banner.create'));
        });

    Route::post('/', [BannerGroupController::class, 'store'])->name('store');
});
