<?php

use App\Domains\Contact\Http\Controllers\Backend\ContactController;
use App\Domains\Post\Models\ContactUs;
use Tabuna\Breadcrumbs\Trail;

// All route names are prefixed with 'admin.'.
Route::group([
                 'prefix' => 'whistleblowing',
                 'as' => 'whistleblowing.',
             ], function () {
    Route::get('/', [ContactController::class, 'whistleblowing'])
    ->name('index') // Change from 'whistleblowing' to 'index'
    ->breadcrumbs(function (Trail $trail, $type = "") {
        $trail->parent('admin.dashboard')
            ->push(__(ucwords($type)), route('admin.whistleblowing.index', $type));
    });


    Route::group(['prefix' => '{contact}'], function () {
        Route::get('/show', [ContactController::class, 'show'])
            ->name('show')
            ->breadcrumbs(function (Trail $trail, ContactUs $contact) {
                $trail->parent('admin.whistleblowing.index')
                    ->push("$contact->firstname $contact->lastname", route('admin.whistleblowing.show', ['contact' => $contact]));
            });
        Route::delete('/', [ContactController::class, 'whistleblowingDelete'])->name('destroy');
    });
});
