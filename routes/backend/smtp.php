<?php

use App\Domains\Core\Http\Controllers\Backend\SmtpController;
use Tabuna\Breadcrumbs\Trail;

// All route names are prefixed with 'admin.'.
Route::group([
    'prefix' => 'smtp',
    'as' => 'smtp.',
], function () {
    Route::get('/', [SmtpController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('SMTP Settings'), route('admin.dashboard'));
        });

    Route::post('/', [SmtpController::class, 'store'])->name('store');
    Route::get('/edit', [SmtpController::class, 'edit'])->name('edit');
    Route::get('/test', [SmtpController::class, 'sendTesting'])->name('test');
});
