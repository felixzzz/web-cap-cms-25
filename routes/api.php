<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/products', [\App\Http\Controllers\Api\PostController::class, 'products']);
Route::get('/products/json', [\App\Http\Controllers\Api\PostController::class, 'productJson']);
Route::prefix('form')->group(function () {
    Route::get('/fields/{form}', [\App\Http\Controllers\Api\SubmissionController::class, 'getFields']);
    Route::post('/submit/{form}', [\App\Http\Controllers\Api\SubmissionController::class, 'submitForm']);
});
Route::prefix('pages')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\PagesController::class, 'index']);
    Route::get('/dynamic', [\App\Http\Controllers\Api\PagesController::class, 'pageDynamic']);
    Route::get('/slug', [\App\Http\Controllers\Api\PagesController::class, 'pageSlugs']);
    Route::get('/{slug}', [\App\Http\Controllers\Api\PagesController::class, 'getDetailbySlug']);
});
Route::prefix('posts')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\PostController::class, 'index']);
    Route::get('/categories', [\App\Http\Controllers\Api\PostController::class, 'categories']);
    Route::get('/{slug}', [\App\Http\Controllers\Api\PostController::class, 'getPostDetailbySlug']);
});
Route::get('/general-search', [\App\Http\Controllers\Api\PagesController::class, 'search']);
Route::post('/contact-us', [\App\Http\Controllers\Api\SubmissionController::class, 'contactUs']);
Route::get('/documents', [\App\Http\Controllers\Api\SubmissionController::class, 'documents']);
Route::get('/documents/published-years', [\App\Http\Controllers\Api\SubmissionController::class, 'getPublishedYears']);
Route::get('/documents/categories', [\App\Http\Controllers\Api\SubmissionController::class, 'documentsCategories']);
Route::post('/documents/post-by-session', [\App\Http\Controllers\Api\SubmissionController::class, 'documentBySession']);
Route::get('/documents/pending/{sessionId}', [\App\Http\Controllers\Api\SubmissionController::class, 'getPendingDocumentBySession']);
Route::post('/documents/bulk-download', [\App\Http\Controllers\Api\SubmissionController::class, 'bulkDocumentDownload']);
Route::get('/banner/{slug}', [\App\Http\Controllers\Api\BannerController::class, 'getBannersByPostSlug']);
Route::get('/banner-active/{id}', [\App\Http\Controllers\Api\BannerController::class, 'getBannerActiveById']);
Route::get('/home-banners', [\App\Http\Controllers\Api\BannerController::class, 'getHomeBanners']);

