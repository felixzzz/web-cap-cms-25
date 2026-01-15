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
});
