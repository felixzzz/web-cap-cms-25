<?php

use App\Domains\PostCategory\Http\Controllers\Backend\CategoryController;
use App\Domains\PostCategory\Models\Category;
use Tabuna\Breadcrumbs\Trail;

// All route names are prefixed with 'admin.'.
Route::group([
                 'prefix' => 'categories',
                 'as' => 'category.',
             ], function () {
    Route::get('/{type}', [CategoryController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail,$type="") {
            $trail->parent('admin.dashboard')
                ->push(__(ucwords($type)), route('admin.category.index',$type));
        });

    Route::get('/trashed/{type}', [CategoryController::class, 'trashed'])
        ->name('deleted')
        ->breadcrumbs(function (Trail $trail,$type="") {
            $trail->parent('admin.category.index',$type)
                ->push(__('Trashed'), route('admin.category.deleted',$type));
        });

    Route::get('/create/{type}', [CategoryController::class, 'create'])
        ->name('create')
        ->breadcrumbs(function (Trail $trail,$type="") {
            $trail->parent('admin.category.index',$type)
                ->push(__('Create '.ucwords($type)), route('admin.category.create',$type));
        });

    Route::post('/{type}', [CategoryController::class, 'store'])->name('store');

    Route::group(['prefix' => '{category}'], function () {
       Route::get('/show/{type}', [CategoryController::class, 'show'])
            ->name('show');


        Route::get('edit', [CategoryController::class, 'edit'])
            ->name('edit');

        Route::patch('/', [CategoryController::class, 'update'])->name('update');
        Route::delete('/', [CategoryController::class, 'delete'])->name('destroy');

        Route::patch('restore', [CategoryController::class, 'restore'])->name('restore');
        Route::delete('permanently-delete', [CategoryController::class, 'destroy'])->name('permanently-delete');
    });
});
