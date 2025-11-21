<?php

use App\Domains\Core\Http\Controllers\Backend\GeneralController;
use App\Domains\Page\Http\Controllers\ImageController;
use Tabuna\Breadcrumbs\Trail;

Route::prefix('page')->group(function () {
    Route::get('/', [\App\Domains\Page\Http\Controllers\PageController::class, 'index'])->name('page')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Page'));
        });
    Route::get('/template/{template}', [\App\Domains\Page\Http\Controllers\PageController::class, 'editbyTemplate'])->name('page.template')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Page'), route('admin.page'))
                ->push(__('Create Oor Edit Page'));
        });
    Route::get('/create', [\App\Domains\Page\Http\Controllers\PageController::class, 'create'])->name('page.create')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Page'), route('admin.page'))
                ->push(__('Create Page'));
        });

    Route::post('/image', [ImageController::class, 'imageUpload'])->name('image');
    Route::post('/store', [\App\Domains\Page\Http\Controllers\PageController::class, 'store'])->name('page.store');
    Route::get('/trashed', [\App\Domains\Page\Http\Controllers\PageController::class, 'trashed'])
        ->name('page.deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Page'), route('admin.page'))
                ->push(__('Trashed'));
        });
    Route::prefix('show/{post}')->group(function () {
        Route::get('/', [\App\Domains\Page\Http\Controllers\PageController::class, 'edit'])->name('page.edit')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.dashboard')
                    ->push(__('Page'), route('admin.page'))
                    ->push(__('Edit Page'));
            });
        Route::get('/details', [\App\Domains\Page\Http\Controllers\PageController::class, 'show'])->name('page.show')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.dashboard')
                    ->push(__('Page'), route('admin.page'))
                    ->push(__('Show Page'));
            });
        Route::patch('/update', [\App\Domains\Page\Http\Controllers\PageController::class, 'update'])->name('page.update');
        Route::get('/change-status', [\App\Domains\Page\Http\Controllers\PageController::class, 'changeStatus'])->name('page.status');

        Route::delete('/', [\App\Domains\Page\Http\Controllers\PageController::class, 'delete'])->name('page.destroy');

        Route::patch('restore', [\App\Domains\Page\Http\Controllers\PageController::class, 'restore'])->name('page.restore');
        Route::delete('permanently-delete', [\App\Domains\Page\Http\Controllers\PageController::class, 'destroy'])->name('page.permanently-delete');
    });
});
