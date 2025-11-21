<?php

namespace App\Domains\Page\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function imageUpload(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg,webp,docs,pdf,doc,mp4,mov,avi,wmv',
        ]);

        $file = $request->image;

        $path = $file->storePublicly('images/post');

        return response()->json([
            "success" => true,
            "message" => "Image successfully uploaded",
            "full_path" => Storage::url($path),
            "path" => $path
        ]);
    }
}
