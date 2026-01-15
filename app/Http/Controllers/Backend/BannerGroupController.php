<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BannerGroupController extends Controller
{
    public function index()
    {
        return view('backend.banner.index');
    }

    public function create()
    {
        $field = [
            'label' => 'Banners',
            'name' => 'banners',
            'list' => [
                ['type' => 'text', 'name' => 'title', 'label' => 'Title'],
                ['type' => 'textarea', 'name' => 'description', 'label' => 'Description'],
                ['type' => 'image', 'name' => 'image', 'label' => 'Image', 'info' => 'Upload main image'],
                ['type' => 'text', 'name' => 'video_url', 'label' => 'Video URL'],
                ['type' => 'textarea', 'name' => 'html_content', 'label' => 'HTML Content'],
                ['type' => 'text', 'name' => 'cta_url', 'label' => 'CTA URL'],
                ['type' => 'text', 'name' => 'cta_label', 'label' => 'CTA Label'],
                ['type' => 'text', 'name' => 'cta_gtm', 'label' => 'CTA GTM'],
            ]
        ];

        return view('backend.banner.create', compact('field'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            // 'slug' => 'required|unique:banner_groups,slug',
            'banners' => 'nullable',
            'bulk_position' => 'nullable'
        ]);

        $banners = [];
        if (isset($data['banners'])) {
             $banners = json_decode($data['banners'], true);
             unset($data['banners']);
        }

        dd($data, $banners);

        \DB::transaction(function () use ($data, $banners) {
            $group = BannerGroup::create($data);

            if (!empty($banners)) {
                foreach ($banners as $index => $bannerData) {
                    $group->banners()->create([
                        'order' => $index,
                        'title' => $bannerData['title'] ?? null,
                        'description' => $bannerData['description'] ?? null,
                        'image' => $bannerData['image'] ?? null,
                        'video' => $bannerData['video_url'] ?? null, // Map video_url to video
                        'html' => $bannerData['html_content'] ?? null, // Map html_content to html
                        'cta_url' => $bannerData['cta_url'] ?? null,
                        'cta_label' => $bannerData['cta_label'] ?? null,
                        'cta_gtm' => $bannerData['cta_gtm'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('admin.banner.index')->withFlashSuccess(__('Banner Group Created Successfully.'));
    }
}
