<?php

use App\Domains\Post\Http\Controllers\Backend\CustomPostController;
use App\Http\Controllers\LocaleController;

/*
 * Global Routes
 *
 * Routes that are used between both frontend and backend.
 */

// Switch between the included languages
Route::get('lang/{lang}', [LocaleController::class, 'change'])->name('locale.change');

/*
 * Frontend Routes
 */
Route::name('frontend.')->group(function () {
    includeRouteFiles(__DIR__.'/frontend/');
});

/*
 * Backend Routes
 *
 * These routes can only be accessed by users with type `admin`
 */
Route::prefix(config('app.admin_prefix'))->name('admin.')->middleware('admin')->group(function () {
    includeRouteFiles(__DIR__.'/backend/');
});

Route::get('/test', [\App\Http\Controllers\Backend\TestController::class, 'index']);

Route::prefix('cpt')->middleware('admin')->group(function () {
    Route::get('/', [CustomPostController::class, 'index'])->name('post.type.custom');
    Route::post('/', [CustomPostController::class, 'store'])->name('post.type.custom.store');
});
