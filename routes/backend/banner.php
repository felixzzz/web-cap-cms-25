<?php

use App\Http\Controllers\Backend\BannerGroupController;
use Tabuna\Breadcrumbs\Trail;
use App\Models\BannerGroup;

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

    Route::post('active/embedded', [BannerGroupController::class, 'storeActiveEmbedded'])->name('active.embedded');

    Route::get('/create', [BannerGroupController::class, 'create'])
        ->name('create')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.banner.index')
                ->push(__('Create Banner'), route('admin.banner.create'));
        });

    Route::post('/', [BannerGroupController::class, 'store'])->name('store');

    Route::group(['prefix' => '{banner_group}'], function () {
        Route::get('edit', [BannerGroupController::class, 'edit'])
            ->name('edit')
            ->breadcrumbs(function (Trail $trail, BannerGroup $banner) {
                $trail->parent('admin.banner.index')
                    ->push(__('Edit Banner'), route('admin.banner.edit', $banner));
            });

        Route::patch('/', [BannerGroupController::class, 'update'])->name('update');
        Route::delete('/', [BannerGroupController::class, 'destroy'])->name('destroy');
    });
});
