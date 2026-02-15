<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ajax\FilepondRequest;
use App\Models\Extra\TemporaryUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function filepond(FilepondRequest $request)
    {
        $arr = '';
        if (count($request->file()) > 0) {
            foreach ($request->file() as $key => $value) {
                $create = TemporaryUpload::create([
                    'filename' => $request->file($key)->getClientOriginalName(),
                ]);
                if ($create) {
                    $create->addMediaFromRequest($key)
                        ->toMediaCollection();
                    $arr = $create->id;
                }
            }
        }
        return $arr;
    }

    public function filepondLocal(FilepondRequest $request)
    {
        $arr = '';
        if (count($request->file()) > 0) {
            foreach ($request->file() as $key => $value) {
                $create = TemporaryUpload::create([
                    'filename' => $request->file($key)->getClientOriginalName(),
                ]);
                if ($create) {
                    $create->addMediaFromRequest($key)
                        ->toMediaCollection('default', 'public');
                    $arr = $create->id;
                }
            }
        }
        return $arr;
    }

    public function storeImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            // $originName = $request->file('upload')->getClientOriginalName();
            // $fileName = pathinfo($originName, PATHINFO_FILENAME);
            // $extension = $request->file('upload')->getClientOriginalExtension();
            // $fileName = $fileName . '_' . time() . '.' . $extension;

            // $request->file('upload')->move(public_path('media'), $fileName);
            $file = $request->upload;
            $path = $file->storePublicly('images/post');
            $url = Storage::url($path);

            // $url = asset('media/' . $fileName);
            return response()->json(['uploaded' => 1, 'url' => $url]);
        }
    }
}
