<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BannerGroup;
use App\Models\Extra\TemporaryUpload;
use Illuminate\Support\Facades\Storage;

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
                [
                    'type' => 'select',
                    'name' => 'aspect_ratio',
                    'label' => 'Aspect Ratio',
                    'options' => [
                        ['value' => '4/3', 'label' => '4:3'],
                        ['value' => '1/1', 'label' => '1:1'],
                        ['value' => '16/9', 'label' => '16:9'],
                        ['value' => '3/4', 'label' => '3:4 (Portrait)'],
                        ['value' => '9/16', 'label' => '9:16 (Portrait)'],
                    ]
                ],
                ['type' => 'image', 'name' => 'image', 'label' => 'Image', 'info' => 'Upload main image'],
                ['type' => 'video', 'name' => 'video', 'label' => 'Video', 'info' => 'Upload video (Max 2MB)'],
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
                    $group->items()->create([
                        'order' => $index,
                        'title' => $bannerData['title'] ?? null,
                        'description' => $bannerData['description'] ?? null,
                        'image' => $this->resolveImage($bannerData['image'] ?? null),
                        'aspect_ratio' => $bannerData['aspect_ratio'] ?? null,
                        'video' => $this->resolveImage($bannerData['video'] ?? null), // Map video input to video column
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
                [
                    'type' => 'select',
                    'name' => 'aspect_ratio',
                    'label' => 'Aspect Ratio',
                    'options' => [
                        ['value' => '1/1', 'label' => '1:1'],
                        ['value' => '4/3', 'label' => '4:3'],
                        ['value' => '16/9', 'label' => '16:9'],
                        ['value' => '3/4', 'label' => '3:4 (Portrait)'],
                        ['value' => '9/16', 'label' => '9:16 (Portrait)'],
                    ]
                ],
                ['type' => 'image', 'name' => 'image', 'label' => 'Image', 'info' => 'Upload main image'],
                ['type' => 'video', 'name' => 'video', 'label' => 'Video', 'info' => 'Upload video (Max 2MB)'],
                ['type' => 'textarea', 'name' => 'html_content', 'label' => 'HTML Content'],
                ['type' => 'text', 'name' => 'cta_url', 'label' => 'CTA URL'],
                ['type' => 'text', 'name' => 'cta_label', 'label' => 'CTA Label'],
                ['type' => 'text', 'name' => 'cta_gtm', 'label' => 'CTA GTM'],
            ]
        ];

        // Prepare banners for repeater
        $banner_group->load('items');
        $banners = $banner_group->items()->orderBy('order')->get()->map(function ($banner) {
            return [
                'title' => $banner->title,
                'description' => $banner->description,
                'image' => $banner->image,
                'aspect_ratio' => $banner->aspect_ratio,
                'video' => $banner->video,
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
            $banner_group->items()->delete();

            if (!empty($banners)) {
                foreach ($banners as $index => $bannerData) {
                    $banner_group->items()->create([
                        'order' => $index,
                        'title' => $bannerData['title'] ?? null,
                        'description' => $bannerData['description'] ?? null,
                        'image' => $this->resolveImage($bannerData['image'] ?? null),
                        'aspect_ratio' => $bannerData['aspect_ratio'] ?? null,
                        'video' => $this->resolveImage($bannerData['video'] ?? null),
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

    private function resolveImage($image)
    {
        if (is_numeric($image)) {
            $temp = TemporaryUpload::find($image);
            if ($temp) {
                $media = $temp->getFirstMedia();
                if ($media) {
                    $filename = $media->file_name;
                    $uniqueFilename = time() . '_' . $filename;
                    $destPath = 'images/banners/' . $uniqueFilename;
                    Storage::disk('public')->put($destPath, file_get_contents($media->getPath()));
                    $temp->delete();
                    return $destPath;
                }
            }
        }
        return $image;
    }

    public function storeActiveEmbedded(Request $request)
    {
        $data = $request->validate([
            'banner_group_id' => 'required|exists:banner_groups,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $bannerActive = \App\Models\BannerActive::create([
            'banner_group_id' => $data['banner_group_id'],
            'location' => 'embedded', // Special location for embedded banners
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'language' => app()->getLocale(), // active locale
            'post_id' => null, // Embedded banners might be tied to content, but here we just create the record
        ]);

        return response()->json([
            'success' => true,
            'id' => $bannerActive->id,
            'group_title' => $bannerActive->bannerGroup->title,
            'start_date' => $bannerActive->start_date ? $bannerActive->start_date->format('Y-m-d H:i') : 'No Start Date',
            'end_date' => $bannerActive->end_date ? $bannerActive->end_date->format('Y-m-d H:i') : 'No End Date',
        ]);
    }
}
