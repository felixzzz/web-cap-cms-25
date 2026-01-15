<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BannerGroup;

class BannerGroupController extends Controller
{
    public function index()
    {
        return view('backend.banner.index');
    }

    public function create()
    {
        $field = [
            'label' => 'Banner Items',
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
            'banners' => 'nullable',
            'bulk_position' => 'nullable'
        ]);

        $banners = [];
        if (isset($data['banners'])) {
            $banners = json_decode($data['banners'], true);
            unset($data['banners']);
        }

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

    public function edit(BannerGroup $banner_group)
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

        // Prepare banners for repeater
        $banner_group->load('banners');
        $banners = $banner_group->banners()->orderBy('order')->get()->map(function ($banner) {
            return [
                'title' => $banner->title,
                'description' => $banner->description,
                'image' => $banner->image,
                'video_url' => $banner->video,
                'html_content' => $banner->html,
                'cta_url' => $banner->cta_url,
                'cta_label' => $banner->cta_label,
                'cta_gtm' => $banner->cta_gtm,
            ];
        })->toArray();

        return view('backend.banner.edit', compact('banner_group', 'field', 'banners'));
    }

    public function update(Request $request, BannerGroup $banner_group)
    {
        $data = $request->validate([
            'title' => 'required',
            'banners' => 'nullable',
            'bulk_position' => 'nullable'
        ]);

        $banners = [];
        if (isset($data['banners'])) {
            $banners = json_decode($data['banners'], true);
            unset($data['banners']);
        }

        \DB::transaction(function () use ($data, $banners, $banner_group) {
            $banner_group->update($data);

            // Delete existing banners and recreate (simplest strategy for reordering)
            $banner_group->banners()->delete();

            if (!empty($banners)) {
                foreach ($banners as $index => $bannerData) {
                    $banner_group->banners()->create([
                        'order' => $index,
                        'title' => $bannerData['title'] ?? null,
                        'description' => $bannerData['description'] ?? null,
                        'image' => $bannerData['image'] ?? null,
                        'video' => $bannerData['video_url'] ?? null,
                        'html' => $bannerData['html_content'] ?? null,
                        'cta_url' => $bannerData['cta_url'] ?? null,
                        'cta_label' => $bannerData['cta_label'] ?? null,
                        'cta_gtm' => $bannerData['cta_gtm'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('admin.banner.index')->withFlashSuccess(__('Banner Group Updated Successfully.'));
    }

    public function destroy(BannerGroup $banner_group)
    {
        $banner_group->delete();
        return redirect()->back()->withFlashSuccess(__('Banner Group Deleted Successfully.'));
    }
}
