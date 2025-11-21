<?php

use App\Domains\Core\Http\Controllers\Backend\GeneralController;
use App\Http\Controllers\Backend\ComponentController;
use Tabuna\Breadcrumbs\Trail;

// All route names are prefixed with 'admin.'.
Route::group([
    'prefix' => 'component',
    'as' => 'component.',
], function () {
    Route::group(['prefix' => 'image', 'as' => 'image.'], function () {
        Route::get('/', [ComponentController::class, 'image'])
            ->name('index')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.dashboard')
                    ->push(__('Component'), route('admin.dashboard'));
            });
        Route::post('/validation', [ComponentController::class, 'validateImage'])
            ->name('validation');
        Route::post('/resize', [ComponentController::class, 'resizeImage'])
            ->name('resize');
        Route::post('/crop', [ComponentController::class, 'cropImage'])
            ->name('crop');
    });
});
