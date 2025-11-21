<?php

use App\Domains\Contact\Http\Controllers\Backend\ContactController;
use App\Domains\Post\Models\ContactUs;
use Tabuna\Breadcrumbs\Trail;

// All route names are prefixed with 'admin.'.
Route::group([
                 'prefix' => 'contact',
                 'as' => 'contact.',
             ], function () {
    Route::get('/', [ContactController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail,$type="") {
            $trail->parent('admin.dashboard')
                ->push(__(ucwords($type)), route('admin.contact.index',$type));
        });

    Route::group(['prefix' => '{contact}'], function () {
        Route::get('/show', [ContactController::class, 'show'])
            ->name('show')
            // ->middleware('permission:admin.access.user.list')
            ->breadcrumbs(function (Trail $trail, ContactUs $contact) {
                $trail->parent('admin.contact.index')
                    ->push($contact->firstname, route('admin.contact.show', ['contact' => $contact]));
            });
        Route::delete('/', [ContactController::class, 'delete'])->name('destroy');
    });
});
