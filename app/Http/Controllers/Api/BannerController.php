<?php

namespace App\Http\Controllers\Api;

use App\Domains\Post\Models\Post;
use App\Http\Controllers\Controller;
use App\Models\BannerActive;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Get active banners for a post by its slug, grouped by location.
     *
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBannersByPostSlug($slug)
    {
        try {
            $post = Post::where('slug', $slug)
                ->orWhere('slug_en', $slug)
                ->firstOrFail();

            $lang = 'id';
            if ($post->slug_en === $slug) {
                $lang = 'en';
            }

            $activeBanners = BannerActive::where('post_id', $post->id)
                ->where('language', $lang)
                ->with(['bannerGroup.items'])
                ->get();

            $response = [
                'left' => [],
                'right' => [],
                'center' => [],
                'bottom' => []
            ];

            // Process and group banners
            foreach ($activeBanners as $activeBanner) {
                $location = strtolower($activeBanner->location);
                \Illuminate\Support\Facades\Log::info("Processing BannerActive ID: {$activeBanner->id}, Location: {$location}");

                // Validate location key exists in our response structure
                if (array_key_exists($location, $response)) {
                    // If a banner group is attached, merge its banners into the location array
                    if ($activeBanner->bannerGroup) {
                        if ($activeBanner->bannerGroup->items) {
                            foreach ($activeBanner->bannerGroup->items as $banner) {
                                $response[$location][] = $banner;
                            }
                        }
                    }
                }
            }

            return response()->json($response);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Not Found',
                'message' => 'Post not found for the given slug.'
            ], 404);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Error fetching banners: " . $e->getMessage());
            return response()->json([
                'error' => 'Server Error',
                'message' => 'An error occurred while fetching banners.'
            ], 500);
        }
    }

    /**
     * Get active banner by ID and return its banner group items.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBannerActiveById($id)
    {
        try {
            $activeBanner = BannerActive::with(['bannerGroup.items'])
                ->findOrFail($id);

            $banners = [];
            if ($activeBanner->bannerGroup && $activeBanner->bannerGroup->items) {
                $banners = $activeBanner->bannerGroup->items;
            }

            return response()->json($banners);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Not Found',
                'message' => 'Banner Active not found.'
            ], 404);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Error fetching banner active: " . $e->getMessage());
            return response()->json([
                'error' => 'Server Error',
                'message' => 'An error occurred while fetching banner active.'
            ], 500);
        }
    }

    /**
     * Get active banners for home page grouped by position.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHomeBanners(Request $request)
    {
        try {
            $homePage = Post::where('site_url', '/')->firstOrFail();

            $lang = $request->input('lang', 'id');
            $activeBanners = BannerActive::where('post_id', $homePage->id)
                ->where('language', $lang)
                ->with(['bannerGroup.items'])
                ->get();

            $response = [
                'navbar' => [],
                'journey-growth' => [],
                'financial-reports' => [],
                'footer' => []
            ];

            // Process and group banners
            foreach ($activeBanners as $activeBanner) {
                $location = strtolower($activeBanner->location);
                \Illuminate\Support\Facades\Log::info("Processing Home BannerActive ID: {$activeBanner->id}, Location: {$location}");

                // Validate location key exists in our response structure
                if (array_key_exists($location, $response)) {
                    // If a banner group is attached, merge its banners into the location array
                    if ($activeBanner->bannerGroup) {
                        if ($activeBanner->bannerGroup->items) {
                            foreach ($activeBanner->bannerGroup->items as $banner) {
                                $response[$location][] = $banner;
                            }
                        }
                    }
                }
            }

            return response()->json($response);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Not Found',
                'message' => 'Home page not found.'
            ], 404);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Error fetching home banners: " . $e->getMessage());
            return response()->json([
                'error' => 'Server Error',
                'message' => 'An error occurred while fetching home banners.'
            ], 500);
        }
    }
}
