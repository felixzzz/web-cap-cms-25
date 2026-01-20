<?php

use App\Http\Controllers\Ajax\PostController;
use App\Http\Controllers\Ajax\UploadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::prefix('upload')->group(function () {
    Route::match(['delete', 'post'], '/filepond', [UploadController::class, 'filepond'])->name('ajax.filepond');
    Route::match(['delete', 'post'], '/filepond-local', [UploadController::class, 'filepondLocal'])->name('ajax.filepond.local');
});

Route::prefix('posts')->group(function () {
    Route::post('status', [PostController::class, 'status'])->name('ajax.post.status');
});
