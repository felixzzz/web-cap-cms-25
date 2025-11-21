<?php

use App\Domains\Core\Http\Controllers\Backend\GeneralController;
use App\Domains\Page\Http\Controllers\ImageController;
use Tabuna\Breadcrumbs\Trail;

Route::prefix('dynamic-template')->group(function () {
    Route::get('/', [\App\Domains\Page\Http\Controllers\DynamicController::class, 'index'])->name('dynamic')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Page'));
        });
    Route::get('/create', [\App\Domains\Page\Http\Controllers\DynamicController::class, 'create'])->name('dynamic.create')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Page'), route('admin.dynamic'))
                ->push(__('Create Dynamic Template'));
        });

    Route::post('/image', [ImageController::class, 'imageUpload'])->name('image');
    Route::post('/store', [\App\Domains\Page\Http\Controllers\DynamicController::class, 'store'])->name('dynamic.store');
    Route::get('/trashed', [\App\Domains\Page\Http\Controllers\DynamicController::class, 'trashed'])
        ->name('dynamic.deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Page'), route('admin.dynamic'))
                ->push(__('Trashed'));
        });
    Route::prefix('show/{post}')->group(function () {
        Route::get('/', [\App\Domains\Page\Http\Controllers\DynamicController::class, 'edit'])->name('dynamic.edit')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.dashboard')
                    ->push(__('Page'), route('admin.dynamic'))
                    ->push(__('Edit Dynamic Template'));
            });
        Route::get('/details', [\App\Domains\Page\Http\Controllers\DynamicController::class, 'show'])->name('dynamic.show')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.dashboard')
                    ->push(__('Page'), route('admin.dynamic'))
                    ->push(__('Show Dynamic Template'));
            });
        Route::patch('/update', [\App\Domains\Page\Http\Controllers\DynamicController::class, 'update'])->name('dynamic.update');
        Route::get('/change-status', [\App\Domains\Page\Http\Controllers\DynamicController::class, 'changeStatus'])->name('dynamic.status');

        Route::delete('/', [\App\Domains\Page\Http\Controllers\DynamicController::class, 'delete'])->name('dynamic.destroy');

        Route::patch('restore', [\App\Domains\Page\Http\Controllers\DynamicController::class, 'restore'])->name('dynamic.restore');
        Route::delete('permanently-delete', [\App\Domains\Page\Http\Controllers\DynamicController::class, 'destroy'])->name('dynamic.permanently-delete');
    });
});
