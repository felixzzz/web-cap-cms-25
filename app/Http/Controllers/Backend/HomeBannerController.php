<?php

namespace App\Http\Controllers\Backend;

use App\Domains\Post\Models\Post;
use App\Models\BannerGroup;
use App\Models\BannerActive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class HomeBannerController extends Controller
{
    /**
     * Show the form for editing home page banner configuration.
     */
    public function edit()
    {
        // Get the home page
        $homePage = Post::where('site_url', '/')->first();

        if (!$homePage) {
            return redirect()->back()->withFlashDanger('Home page not found.');
        }

        // Get all banner groups with item counts
        $bannerGroups = BannerGroup::withCount('items')->get();

        // Define the positions for home page banners
        $positions = ['navbar', 'journey-growth', 'financial-reports'];

        // Define language options
        $languages = [
            ['name' => 'Indonesian', 'value' => 'id'],
            ['name' => 'English', 'value' => 'en']
        ];

        return view('backend.home-banners.edit', compact('homePage', 'bannerGroups', 'positions', 'languages'));
    }

    /**
     * Update home page banner configuration.
     */
    public function update(Request $request)
    {
        // Get the home page
        $homePage = Post::where('site_url', '/')->first();

        if (!$homePage) {
            return redirect()->back()->withFlashDanger('Home page not found.');
        }

        DB::beginTransaction();

        try {
            // Sync banner active records
            $this->syncBannerActive($homePage, $request->input('banner_active', []));

            DB::commit();

            return redirect()->route('admin.home-banners.edit')
                ->withFlashSuccess('Home page banner configuration updated successfully.');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return redirect()->back()
                ->withFlashDanger('There was a problem updating banner configuration. Please try again.');
        }
    }

    /**
     * Sync banner active records for the home page.
     */
    private function syncBannerActive(Post $post, array $bannerActiveData)
    {
        $existingBanners = $post->activeBanners()->get();
        $keepIds = [];

        foreach ($bannerActiveData as $lang => $locations) {
            foreach ($locations as $location => $data) {
                // If group_id is empty, skip (meaning it was cleared or not set)
                if (empty($data['group_id'])) {
                    continue;
                }

                $activeBanner = $post->activeBanners()
                    ->where('language', $lang)
                    ->where('location', $location)
                    ->first();

                if ($activeBanner) {
                    $activeBanner->banner_group_id = $data['group_id'];
                    $activeBanner->start_date = $data['start_date'] ?? null;
                    $activeBanner->end_date = $data['end_date'] ?? null;
                    $activeBanner->save();
                    $keepIds[] = $activeBanner->id;
                } else {
                    $newBanner = $post->activeBanners()->create([
                        'language' => $lang,
                        'location' => $location,
                        'banner_group_id' => $data['group_id'],
                        'start_date' => $data['start_date'] ?? null,
                        'end_date' => $data['end_date'] ?? null,
                    ]);
                    $keepIds[] = $newBanner->id;
                }
            }
        }

        // Delete banners that are no longer present in the request
        foreach ($existingBanners as $banner) {
            // Check if this banner's lang/location is in the request with a valid group_id
            $inRequest = false;
            if (
                isset($bannerActiveData[$banner->language][$banner->location]['group_id'])
                && !empty($bannerActiveData[$banner->language][$banner->location]['group_id'])
            ) {
                $inRequest = true;
            }

            if (!$inRequest) {
                $banner->delete();
            }
        }
    }
}
