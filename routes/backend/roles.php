<?php
use \App\Domains\Auth\Http\Controllers\Backend\Role\RoleController;

Route::prefix('role')->name('role.')->middleware('role:'.config('boilerplate.access.role.admin'))->group(function () {
    Route::get('/', [RoleController::class, 'index'])
        ->name('index');

    Route::get('create', [RoleController::class, 'create'])
        ->name('create');

    Route::post('/', [RoleController::class, 'store'])->name('store');

    Route::prefix('{role}')->group(function () {
        Route::get('show', [RoleController::class, 'show'])
            ->name('show');

        Route::get('edit', [RoleController::class, 'edit'])
            ->name('edit');

        Route::patch('/', [RoleController::class, 'update'])->name('update');
        Route::delete('/', [RoleController::class, 'destroy'])->name('destroy');
    });
});
