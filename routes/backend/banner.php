<?php

use App\Http\Controllers\Backend\BannerGroupController;
use Tabuna\Breadcrumbs\Trail;
use App\Models\BannerGroup;

Route::group([
    'prefix' => 'banner-article',
    'as' => 'banner.',
], function () {
    Route::get('/', [BannerGroupController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Banner Groups'), route('admin.banner.index'));
        });

    Route::post('active/embedded', [BannerGroupController::class, 'storeActiveEmbedded'])->name('active.embedded');
    Route::get('active/list', [BannerGroupController::class, 'listActiveEmbedded'])->name('active.list');
    Route::post('active/update/{id}', [BannerGroupController::class, 'updateActiveEmbedded'])->name('active.update');
    Route::get('list-json', [BannerGroupController::class, 'listJson'])->name('list-json');

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

Route::group([
    'prefix' => 'banner-homepage',
    'as' => 'banner.home.',
], function () {
    Route::get('/', [BannerGroupController::class, 'index'])
        ->name('index')
        ->defaults('position', 'home')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Homepage Banners'), route('admin.banner.home.index'));
        });

    Route::get('/create', [BannerGroupController::class, 'create'])
        ->name('create')
        ->defaults('position', 'home')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.banner.home.index')
                ->push(__('Create Banner'), route('admin.banner.home.create'));
        });
    
    // We can reuse the store/update/destroy routes or create specific ones if needed.
    // For now, let's just make sure the controller handles the redirect correctly.
    // Since store/update use resource param, we might not need separate routes if POST goes to same controller.
    // However, for clarity in names, let's keep them separate but pointing to same controller actions.
});

Route::group([
    'prefix' => 'banner-pages',
    'as' => 'banner.pages.',
], function () {
    Route::get('/', [BannerGroupController::class, 'index'])
        ->name('index')
        ->defaults('position', 'pages')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Pages Banners'), route('admin.banner.pages.index'));
        });

    Route::get('/create', [BannerGroupController::class, 'create'])
        ->name('create')
        ->defaults('position', 'pages')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.banner.pages.index')
                ->push(__('Create Banner'), route('admin.banner.pages.create'));
        });

    Route::post('/', [BannerGroupController::class, 'store'])->name('store');

    Route::group(['prefix' => '{banner_group}'], function () {
        Route::get('edit', [BannerGroupController::class, 'edit'])
            ->name('edit')
            ->breadcrumbs(function (Trail $trail, BannerGroup $banner) {
                $trail->parent('admin.banner.pages.index')
                    ->push(__('Edit Banner'), route('admin.banner.pages.edit', $banner));
            });

        Route::patch('/', [BannerGroupController::class, 'update'])->name('update');
        Route::delete('/', [BannerGroupController::class, 'destroy'])->name('destroy');
    });
});
