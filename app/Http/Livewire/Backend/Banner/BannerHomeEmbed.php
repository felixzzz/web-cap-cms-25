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
        'financial-report' => 'Financial Report',
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
        $this->validate([
            'location' => 'required|in:journey-growth,financial-report',
            'language' => 'required|in:id,en',
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
        ]);

        $homePost = Post::where('site_url', '/')->first();

        if (!$homePost) {
             $this->dispatchBrowserEvent('swal-error', ['title' => 'Error', 'text' => 'Homepage post not found (site_url = "/").']);
             return;
        }

        // Check for existing active banner in this slot with same language
        $existing = BannerActive::where('post_id', $homePost->id)
            ->where('location', $this->location)
            ->where('language', $this->language)
            ->first();

        if ($existing) {
            $this->dispatchBrowserEvent('confirm-homepage-replace', [
                'existingId' => $existing->id,
                'location' => $this->location
            ]);
            return;
        }

        $this->createHomepageBanner($homePost->id);
    }

    public function forceSaveHomepage()
    {
         $homePost = Post::where('site_url', '/')->first();
         if ($homePost) {
             BannerActive::where('post_id', $homePost->id)
                ->where('location', $this->location)
                ->where('language', $this->language)
                ->delete();
             
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
