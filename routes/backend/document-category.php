<?php

use App\Domains\Document\Http\Controllers\Backend\DocumentCategoryController;
use App\Domains\Document\Models\DocumentCategory;
use Tabuna\Breadcrumbs\Trail;

// All route names are prefixed with 'admin.'.
Route::group([
                 'prefix' => 'document-categories',
                 'as' => 'document-categories.',
             ], function () {
    Route::get('/{template?}', [DocumentCategoryController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail,$type="") {
            $trail->parent('admin.dashboard')
                ->push(__(ucwords($type="")), route('admin.document-categories.index',$type));
        });

    Route::get('/trashed', [DocumentCategoryController::class, 'trashed'])
        ->name('deleted')
        ->breadcrumbs(function (Trail $trail,$type="") {
            $trail->parent('admin.document-categories.index',$type="")
                ->push(__('Trashed'), route('admin.document-categories.deleted',$type));
        });

    Route::get('/create/{template?}', [DocumentCategoryController::class, 'create'])
        ->name('create')
        ->breadcrumbs(function (Trail $trail,$type="") {
            $trail->parent('admin.document-categories.index',$type="")
                ->push(__('Create'), route('admin.document-categories.create',$type));
        });

    Route::post('', [DocumentCategoryController::class, 'store'])->name('store');

    Route::group(['prefix' => '{category}'], function () {
        
        Route::get('/show/{template?}', [DocumentCategoryController::class, 'show'])
            ->name('show')
            // ->middleware('permission:admin.access.user.list')
            ->breadcrumbs(function (Trail $trail, DocumentCategory $category) {
                $trail->parent('admin.document-categories.index', $category)
                    ->push($category, route('admin.document-categories.show', ['category' => $category]));
            });

        Route::get('edit/{template?}', [DocumentCategoryController::class, 'edit'])
            ->name('edit')
            ->breadcrumbs(function (Trail $trail, DocumentCategory $category) {
                $trail->parent('admin.document-categories.show', $category)
                    ->push(__('Edit'), route('admin.document-categories.edit', ['category' => $category]));
            });

        Route::patch('/{template?}', [DocumentCategoryController::class, 'update'])->name('update');
        Route::delete('/{template?}', [DocumentCategoryController::class, 'delete'])->name('destroy');

        Route::patch('restore', [DocumentCategoryController::class, 'restore'])->name('restore');
        Route::delete('permanently-delete', [DocumentCategoryController::class, 'destroy'])->name('permanently-delete');
    });
});
