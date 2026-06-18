<?php

use App\Http\Controllers\Ajax\UploadController;

Route::post('image-upload', [UploadController::class, 'storeImage'])->name('image.upload');
