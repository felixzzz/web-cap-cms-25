<?php

use App\Domains\Document\Http\Controllers\Backend\DocumentController;
use App\Domains\Document\Models\Document;
use Tabuna\Breadcrumbs\Trail;

// All route names are prefixed with 'admin.'.
Route::group([
                 'prefix' => 'documents',
                 'as' => 'document.',
             ], function () {
    Route::get('/create/{template?}', [DocumentController::class, 'create'])
        ->name('create')
        ->breadcrumbs(function (Trail $trail,$type="") {
            $trail->parent('admin.document.index',$type="")
                ->push(__('Create'), route('admin.document.create',$type));
        });
    Route::get('/{template?}', [DocumentController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail,$type="") {
            $trail->parent('admin.dashboard')
                ->push(__('Documents'), route('admin.document.index',$type));
        });

    Route::get('/trashed', [DocumentController::class, 'trashed'])
        ->name('deleted')
        ->breadcrumbs(function (Trail $trail,$type="") {
            $trail->parent('admin.document.index',$type="")
                ->push(__('Trashed'), route('admin.document.deleted',$type));
        });

    

    Route::post('', [DocumentController::class, 'store'])->name('store');

    Route::group(['prefix' => '{document}'], function () {
        Route::get('/show/{template?}', [DocumentController::class, 'show'])
            ->name('show')
            // ->middleware('permission:admin.access.user.list')
            ->breadcrumbs(function (Trail $trail, Document $document) {
                $trail->parent('admin.document.index', $document)
                ->push($document, route('admin.document.show', ['document' => $document]));
            });

        Route::get('edit/{template?}', [DocumentController::class, 'edit'])
            ->name('edit')
            ->breadcrumbs(function (Trail $trail, Document $document) {
                $trail->parent('admin.document.show', $document)
                    ->push(__('Edit'), route('admin.document.edit', ['document' => $document]));
            });

        Route::patch('/{template?}', [DocumentController::class, 'update'])->name('update');
        Route::delete('/{template?}', [DocumentController::class, 'delete'])->name('destroy');
        Route::get('/renames', [DocumentController::class, 'renames'])->name('renames');

        Route::patch('restore', [DocumentController::class, 'restore'])->name('restore');
        Route::delete('permanently-delete', [DocumentController::class, 'destroy'])->name('permanently-delete');
    });
});