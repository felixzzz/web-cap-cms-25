<?php

use App\Domains\Post\Http\Controllers\Backend\PostController;
use App\Domains\Post\Models\Post;
use Tabuna\Breadcrumbs\Trail;

// All route names are prefixed with 'admin.'.
Route::group(['prefix' => 'post-type', 'as' => 'posttype.'], function () {
    Route::get('/', [\App\Http\Controllers\Backend\PostTypeController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail,$type) {
            $trail->parent('admin.dashboard')
                ->push(__('Post Types'), route('admin.posttype.index'));
        });

    Route::get('/create', [\App\Http\Controllers\Backend\PostTypeController::class, 'create'])
        ->name('create')
        ->breadcrumbs(function (Trail $trail,$type) {
            $trail->parent('admin.posttype.index',$type)
                ->push(__('Create Type'), route('admin.posttype.create'));
        });

    Route::post('/store', [\App\Http\Controllers\Backend\PostTypeController::class, 'store'])->name('store');

    Route::get('/trashed', [\App\Http\Controllers\Backend\PostTypeController::class, 'trashed'])
        ->name('deleted')
        ->breadcrumbs(function (Trail $trail,$type) {
            $trail->parent('admin.posttype.index')
                ->push(__('Trashed'), route('admin.posttype.deleted', $type));
        });

    Route::group(['prefix' => '{type}'], function () {
        Route::get('/show', [\App\Http\Controllers\Backend\PostTypeController::class, 'show'])
            ->name('show')
            ->breadcrumbs(function (Trail $trail, \App\Domains\Post\Models\PostType $type) {
                $trail->parent('admin.post.index', $type->slug)
                    ->push($type->name, route('admin.posttype.show', $type));
            });


        Route::get('edit', [\App\Http\Controllers\Backend\PostTypeController::class, 'edit'])
            ->name('edit')
            ->breadcrumbs(function (Trail $trail, \App\Domains\Post\Models\PostType $type) {
                $trail->parent('admin.posttype.show', $type);
            });

        Route::patch('/update', [\App\Http\Controllers\Backend\PostTypeController::class, 'update'])->name('update');
        Route::delete('/delete', [\App\Http\Controllers\Backend\PostTypeController::class, 'delete'])->name('destroy');
    });

    Route::patch('restore', [\App\Http\Controllers\Backend\PostTypeController::class, 'restore'])->name('restore');
    Route::delete('permanently-delete', [\App\Http\Controllers\Backend\PostTypeController::class, 'destroy'])->name('permanently-delete');
});
