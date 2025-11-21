<?php

if (!function_exists('resizeImage')) {
    function resizeImage($imagePath, $width, $height, $maintainAspectRatio = false): \Intervention\Image\Image
    {
        $image = Image::make($imagePath);
        $image->resize($width, $height, function ($constraint) use ($maintainAspectRatio) {
            if ($maintainAspectRatio) {
                $constraint->aspectRatio();
            }
        });
        return $image;
    }
}

/* crop image*/
if (!function_exists('cropImage')) {
    function cropImage($imagePath, $width, $height, $x, $y): \Intervention\Image\Image
    {
        $image = Image::make($imagePath);
        $image->crop($width, $height, $x, $y);
        return $image;
    }
}

