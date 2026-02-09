<?php

namespace App\Http\Livewire\Backend\Banner;

use Livewire\Component;
use App\Models\BannerGroup;
use App\Models\BannerActive;
use App\Domains\Post\Models\Post;

class BannerHomeEmbed extends Component
{
    public $bannerGroupId;
    public $bannerGroupTitle;
    public $location;
    public $language = 'id';
    public $startDate;
    public $endDate;

    public $homepageSlots = [
        'journey-growth' => 'Journey Growth',
        'financial-reports' => 'Financial Reports',
    ];

    protected $listeners = ['openBannerHomeEmbed' => 'openModal', 'forceSaveHomepage' => 'forceSaveHomepage'];

    public function openModal($bannerGroupId)
    {
        $this->bannerGroupId = $bannerGroupId;
        $bannerGroup = BannerGroup::find($bannerGroupId);
        $this->bannerGroupTitle = $bannerGroup ? $bannerGroup->title : '';

        $this->location = 'journey-growth';
        $this->language = 'id';
        $this->startDate = null;
        $this->endDate = null;

        $this->dispatchBrowserEvent('open-banner-home-embed-modal');
    }

    public function save()
    {
        try {
            $this->validate([
                'location' => 'required|in:journey-growth,financial-reports',
                'language' => 'required|in:id,en',
                'startDate' => 'required|date',
                'endDate' => 'required|date|after_or_equal:startDate',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatchBrowserEvent('swal-error', ['title' => 'Validation Error!', 'text' => 'Please check the input fields.']);
            throw $e;
        }

        $homePost = Post::where('site_url', '/')->first();

        if (!$homePost) {
            $this->dispatchBrowserEvent('swal-error', ['title' => 'Error', 'text' => 'Homepage post not found (site_url = "/").']);
            return;
        }

        // Check for conflicts with date overlap
        $query = BannerActive::where('post_id', $homePost->id)
            ->where('location', $this->location)
            ->where('language', $this->language);

        $start = $this->startDate;
        $end = $this->endDate;

        $query->where(function ($q) use ($start, $end) {
            if ($end) {
                $q->where(function ($sub) use ($end) {
                    $sub->whereNull('start_date')
                        ->orWhere('start_date', '<=', $end);
                });
            }
            if ($start) {
                $q->where(function ($sub) use ($start) {
                    $sub->whereNull('end_date')
                        ->orWhere('end_date', '>=', $start);
                });
            }
        });

        $conflicts = $query->with('bannerGroup')->get();

        if ($conflicts->count() > 0) {
            $conflictDetails = [];
            foreach ($conflicts as $conflict) {
                // Determine group title
                $grp = $conflict->bannerGroup ? $conflict->bannerGroup->title : 'Unknown Group'; // Fallback
                // Determine dates
                $startStr = $conflict->start_date ? $conflict->start_date->format('Y-m-d') : '∞';
                $endStr = $conflict->end_date ? $conflict->end_date->format('Y-m-d') : '∞';

                $conflictDetails[] = "{$grp} ({$startStr} to {$endStr})";
            }

            // Dispatch warning
            $this->dispatchBrowserEvent('swal:confirm-homepage-overlap', [
                'details' => $conflictDetails,
            ]);
            return;
        }

        $this->createHomepageBanner($homePost->id);
    }

    public function forceSaveHomepage()
    {
        $homePost = Post::where('site_url', '/')->first();
        if ($homePost) {
            // Do NOT delete existing. Logic update: Overlapping banners co-exist.
            // API will filter and show the one with latest Start Date.

            $this->createHomepageBanner($homePost->id);
        }
    }

    protected function createHomepageBanner($postId)
    {
        BannerActive::create([
            'banner_group_id' => $this->bannerGroupId,
            'post_id' => $postId,
            'language' => $this->language,
            'location' => $this->location,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ]);

        $this->closeAndRefresh();
    }

    protected function closeAndRefresh()
    {
        $this->dispatchBrowserEvent('close-banner-home-embed-modal');
        $this->emit('refreshBannerGroupTable');
        $this->dispatchBrowserEvent('flash-message', ['message' => 'Homepage Banner embedded successfully!', 'type' => 'success']);
    }

    public function render()
    {
        return view('livewire.backend.banner.home-embed');
    }
}
