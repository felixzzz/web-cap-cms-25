<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

/**
 * Class DashboardController.
 */
class ComponentController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function image()
    {
        return view('backend.components.image');
    }

    public function validateImage(Request $request){
        $request->validate([
            'validate_image' => [
                'required',
                'image',
                /*file type : mimes:jpeg,png,jpg,gif,svg*/
                'mimes:jpeg,png,jpg,gif,svg',
                /*aspect ratio 16:9*/
                'dimensions:ratio=16/9',
                /* min width 500px */
                'dimensions:min_width=500',
                /*min height 281 px*/
                /*'dimensions:min_height=281'*/
                /*file size : 500 KB*/
                'max:500'
            ],
        ]);
        return redirect()->back()->with('success.validate_image','Image Validated Successfully');
    }

    public function resizeImage(Request $request){
        $request->validate([
            'resize_image' => [
                'required',
                'image',
                /*file type : mimes:jpeg,png,jpg,gif,svg*/
                'mimes:jpeg,png,jpg,gif,svg',
                /*file size : 500 KB*/
                'max:500'
            ],
            'width' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'maintain_aspect_ratio' => 'boolean',
        ]);
        /*upload image*/
        $image = $request->file('resize_image');
        /*get image extension*/
        $extension = $image->getClientOriginalExtension();
        $img = uploadImageToAssetBucket($image, 'images','resize_image_original.'.$extension,'public');
        $newImg = resizeImage($img, $request->width, $request->height, $request->maintain_aspect_ratio);
        $newImagePath = 'images/resize_image_new.'.$extension;
        $newImg->save(storage_path('app/public/'.$newImagePath));

        return redirect()->back()->with('success.resize_image','Image Resize Successfully')
            ->withInput($request->all())
            ->with('beforeResize',$img)
            ->with('afterResize', assetBucket($newImagePath));
    }

    public function cropImage(Request $request){
        $request->validate([
            'crop_image' => [
                'required',
                'image',
                /*file type : mimes:jpeg,png,jpg,gif,svg*/
                'mimes:jpeg,png,jpg,gif,svg',
                /*file size : 500 KB*/
                'max:500',
            ],
            'x' => 'required',
            'y' => 'required',
            'width' => 'required',
            'height' => 'required',
        ]);
        /*upload image*/
        $image = $request->file('crop_image');
        /*get image extension*/
        $extension = $image->getClientOriginalExtension();
        $img = uploadImageToAssetBucket($image, 'images','crop_image_original.'.$extension,'public');
        $newImg = cropImage($img, $request->width, $request->height, $request->x, $request->y);
        $newImagePath = 'images/crop_image_new.'.$extension;
        $newImg->save(storage_path('app/public/'.$newImagePath));

        return redirect()->back()->with('success.crop_image','Image Crop Successfully')
            ->withInput($request->all())
            ->with('beforeCrop',$img)
            ->with('afterCrop', assetBucket($newImagePath));
    }
}
