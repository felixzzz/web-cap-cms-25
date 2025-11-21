<?php

use App\Domains\Post\Http\Controllers\Backend\PostController;
use App\Domains\Post\Models\Post;
use Tabuna\Breadcrumbs\Trail;

// All route names are prefixed with 'admin.'.
Route::group([
                 'prefix' => 'posts',
                 'as' => 'post.',
             ], function () {
    Route::get('/{type}', [PostController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail,$type) {
            $trail->parent('admin.dashboard')
                ->push(__(ucwords(str_replace('-',' ',$type))), route('admin.post.index',$type));
        });

    Route::get('/trashed/{type}', [PostController::class, 'trashed'])
        ->name('deleted')
        ->breadcrumbs(function (Trail $trail,$type) {
            $trail->parent('admin.post.index',$type)
                ->push(__('Trashed'), route('admin.post.deleted',$type));
        });

    Route::get('/create/{type}', [PostController::class, 'create'])
        ->name('create')
        ->breadcrumbs(function (Trail $trail,$type) {
            $trail->parent('admin.post.index',$type)
                ->push(__('Create '.ucwords(str_replace('-',' ',$type))), route('admin.post.create',$type));
        });

    Route::post('/{type}', [PostController::class, 'store'])->name('store');
    Route::group(['prefix' => '{post}'], function () {
        Route::get('/show', [PostController::class, 'show'])
            ->name('show')
            // ->middleware('permission:admin.access.user.list')
            ->breadcrumbs(function (Trail $trail, Post $post) {
                $trail->parent('admin.post.index', $post->type)
                    ->push($post->title, route('admin.post.show', ['post' => $post]));
            });


        Route::get('edit', [PostController::class, 'edit'])
            ->name('edit')
            ->breadcrumbs(function (Trail $trail, Post $post) {
                $trail->parent('admin.post.show', $post);
            });

        Route::patch('/', [PostController::class, 'update'])->name('update');
        Route::delete('/', [PostController::class, 'delete'])->name('destroy');

        Route::patch('restore', [PostController::class, 'restore'])->name('restore');
        Route::delete('permanently-delete', [PostController::class, 'destroy'])->name('permanently-delete');
    });

});
