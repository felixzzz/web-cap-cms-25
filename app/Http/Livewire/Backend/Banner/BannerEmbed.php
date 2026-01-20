<?php

namespace App\Http\Livewire\Backend\Banner;

use Livewire\Component;
use App\Models\BannerGroup;
use App\Models\BannerActive;
use App\Domains\Post\Models\Post;

class BannerEmbed extends Component
{
    public $bannerGroupId;
    public $bannerGroupTitle;
    public $posts = [];
    public $selectedPosts = [];
    public $location = 'center';
    public $search = '';
    public $language = 'all';
    public $postType = 'all';
    public $isAllSelected = false;
    public $startDate;
    public $endDate;

    protected $listeners = ['openBannerEmbed' => 'openModal'];

    public function updatedIsAllSelected($value)
    {
        if ($value) {
            $this->selectedPosts = collect($this->posts)->pluck('id')->toArray();
        } else {
            $this->selectedPosts = [];
        }
    }

    public function mount()
    {
        $this->loadPosts();
    }

    public function updatedSearch()
    {
        $this->loadPosts();
    }

    public function updatedLanguage()
    {
        $this->loadPosts();
    }

    public function updatedPostType()
    {
        $this->loadPosts();
    }

    public function loadPosts()
    {
        $types = ['article', 'blog', 'news'];
        if ($this->postType == 'news') {
            $types = ['news'];
        } elseif ($this->postType == 'blog') {
            $types = ['blog']; // Assuming blog type is 'blog'
        }

        $query = Post::whereIn('type', $types);

        if ($this->postType == 'all') {
            $query = Post::whereIn('type', ['article', 'blog', 'news']);
        }


        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('title_en', 'like', '%' . $this->search . '%');
            });
        }

        $rawPosts = $query->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        $transformedPosts = collect();

        foreach ($rawPosts as $post) {
            // ID Language
            if ($post->title) {
                $transformedPosts->push([
                    'id' => $post->id . '_id',
                    'original_id' => $post->id,
                    'title' => $post->title,
                    'type' => $post->type,
                    'created_at' => $post->created_at,
                    'lang' => 'id'
                ]);
            }

            // EN Language
            if ($post->title_en) {
                $transformedPosts->push([
                    'id' => $post->id . '_en',
                    'original_id' => $post->id,
                    'title' => $post->title_en,
                    'type' => $post->type,
                    'created_at' => $post->created_at,
                    'lang' => 'en'
                ]);
            }
        }

        if ($this->language == 'en') {
            $transformedPosts = $transformedPosts->where('lang', 'en');
        } elseif ($this->language == 'id') {
            $transformedPosts = $transformedPosts->where('lang', 'id');
        }

        $this->posts = $transformedPosts->values()->toArray();
    }

    public function openModal($bannerGroupId)
    {
        $this->bannerGroupId = $bannerGroupId;
        $bannerGroup = BannerGroup::find($bannerGroupId);
        $this->bannerGroupTitle = $bannerGroup ? $bannerGroup->title : '';

        $this->selectedPosts = [];
        $this->location = 'center';
        $this->startDate = null;
        $this->endDate = null;
        $this->dispatchBrowserEvent('open-banner-embed-modal');
    }

    public function save()
    {
        $this->validate([
            'bannerGroupId' => 'required|exists:banner_groups,id',
            'selectedPosts' => 'required|array|min:1',
            'location' => 'required|in:left,right,bottom,center',
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
        ]);

        foreach ($this->selectedPosts as $compositeId) {
            // Parse composite ID (e.g. "10_en")
            $parts = explode('_', $compositeId);
            if (count($parts) < 2)
                continue;

            $postId = $parts[0];
            $lang = $parts[1];

            BannerActive::updateOrCreate(
                [
                    'banner_group_id' => $this->bannerGroupId,
                    'post_id' => $postId,
                    'language' => $lang,
                ],
                [
                    'location' => $this->location,
                    'start_date' => $this->startDate,
                    'end_date' => $this->endDate,
                ]
            );
        }

        $this->dispatchBrowserEvent('close-banner-embed-modal');
        $this->emit('refreshBannerGroupTable');
        $this->dispatchBrowserEvent('flash-message', ['message' => 'Banners embedded successfully!', 'type' => 'success']);
    }

    public function render()
    {
        return view('livewire.backend.banner.embed');
    }
}
