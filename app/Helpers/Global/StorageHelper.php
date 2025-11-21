<?php

if (! function_exists('uploadImageToAssetBucket')) {
    function uploadImageToAssetBucket($file, $path, $filename = null, $disk = null) : ?string
    {
        $name = $filename ?? $file->hashName();
        $pathName = $path . "/" . $name;
        if (Storage::disk($disk)->put($pathName, file_get_contents($file), 'public')) {
            return Storage::disk($disk)->url($pathName);
        }

        return null;
    }
}

if (! function_exists('deleteImageAsset')) {
    function deleteImageAsset($path, $disk = null)
    {
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }
    }
}

if (! function_exists('assetBucket')) {
    function assetBucket($path, $disk = null)
    {
        if (! $path) {
            return "";
        }
        if (Str::startsWith($path, 'http')) {
            return $path;
        }

        if (config('filesystems.default') == 'local') {
            return asset(Storage::url($path));
        } else {
            return Storage::disk($disk)->url($path);
        }
    }
}
