<?php

use App\Domains\Tag\Http\Controllers\Backend\TagController;
use Spatie\Tags\Tag;
use Tabuna\Breadcrumbs\Trail;

// All route names are prefixed with 'admin.'.
Route::group([
    'prefix' => 'tags',
    'as' => 'tag.',
], function () {
    Route::get('/', [TagController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Tag'), route('admin.tag.index'));
        });

    Route::get('trashed', [TagController::class, 'trashed'])
        ->name('deleted')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.tag.index')
                ->push(__('Trashed'), route('admin.tag.deleted'));
        });

    Route::get('create', [TagController::class, 'create'])
        ->name('create')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.tag.index')
                ->push(__('Create Tag'), route('admin.tag.create'));
        });

    Route::post('/', [TagController::class, 'store'])->name('store');

    Route::group(['prefix' => '{tag}'], function () {
        Route::get('/', [TagController::class, 'show'])
            ->name('show')
            ->breadcrumbs(function (Trail $trail, Tag $tag) {
                $trail->parent('admin.tag.index')
                    ->push($tag->id, route('admin.tag.show', $tag));
            });

        Route::get('edit', [TagController::class, 'edit'])
            ->name('edit')
            ->breadcrumbs(function (Trail $trail, Tag $tag) {
                $trail->parent('admin.tag.show', $tag)
                    ->push(__('Edit'), route('admin.tag.edit', $tag));
            });

        Route::patch('/', [TagController::class, 'update'])->name('update');
        Route::delete('/', [TagController::class, 'delete'])->name('destroy');
    });
});
