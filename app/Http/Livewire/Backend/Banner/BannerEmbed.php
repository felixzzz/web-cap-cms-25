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

    public $isHideInMobile = false;

    // listeners moved below

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
            $types = ['blog'];
        }

        $query = Post::whereIn('type', $types);

        if ($this->position == 'pages') {
            $query = Post::where('type', 'page');
        } elseif ($this->postType == 'all') {
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

    public $position = 'article';
    public $start_date;
    public $end_date;
    public $homepageSlots = [
        'journey-growth' => 'Journey Growth',
        'financial-report' => 'Financial Report',
    ];

    protected $listeners = ['openBannerEmbed' => 'openModal', 'forceSaveHomepage' => 'forceSaveHomepage'];

    public function openModal($bannerGroupId, $position = 'article')
    {
        $this->bannerGroupId = $bannerGroupId;
        $bannerGroup = BannerGroup::find($bannerGroupId);
        $this->bannerGroupTitle = $bannerGroup ? $bannerGroup->title : '';
        $this->position = $position;

        $this->selectedPosts = [];
        $this->location = 'center'; // Default loc
        if ($this->position === 'home') {
            $this->location = 'journey-growth'; // Default slot for home
        }

        $this->startDate = null;
        $this->endDate = null;
        $this->isHideInMobile = false;
        $this->dispatchBrowserEvent('open-banner-embed-modal');
    }

    public function save()
    {
        if ($this->position === 'home') {
            $this->saveHomepage();
            return;
        }

        $this->validate([
            'bannerGroupId' => 'required|exists:banner_groups,id',
            'selectedPosts' => 'required|array|min:1',
            'location' => 'required|in:left,right,bottom,center',
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
        ]);

        $conflictsCount = 0;

        foreach ($this->selectedPosts as $compositeId) {
            $parts = explode('_', $compositeId);
            if (count($parts) < 2)
                continue;

            $postId = $parts[0];
            $lang = $parts[1];

            // Check for conflicts
            $query = BannerActive::where('post_id', $postId)
                ->where('language', $lang)
                ->where('location', $this->location);

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

            if ($query->exists()) {
                $conflictsCount++;
            }
        }

        if ($conflictsCount > 0) {
            $this->dispatchBrowserEvent('swal:confirm-replace', [
                'count' => $conflictsCount,
            ]);
            return;
        }

        $this->performSave();
    }

    public function forceSave()
    {
        $this->performSave(true);
    }

    public function performSave($deleteConflicts = false)
    {
        foreach ($this->selectedPosts as $compositeId) {
            $parts = explode('_', $compositeId);
            if (count($parts) < 2)
                continue;

            $postId = $parts[0];
            $lang = $parts[1];

            if ($deleteConflicts) {
                $query = BannerActive::where('post_id', $postId)
                    ->where('language', $lang)
                    ->where('location', $this->location);

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

                $query->delete();
            }

            BannerActive::firstOrCreate(
                [
                    'banner_group_id' => $this->bannerGroupId,
                    'post_id' => $postId,
                    'language' => $lang,
                    'location' => $this->location,
                    'start_date' => $this->startDate,
                    'end_date' => $this->endDate,
                ],
                [
                    'is_hide_in_mobile' => $this->isHideInMobile,
                ]
            );
        }

        $this->closeAndRefresh();
    }

    public function saveHomepage()
    {
        $this->validate([
            'location' => 'required|in:journey-growth,financial-report',
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
        ]);

        // Find homepage post
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
            // Delete existing
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
        $this->dispatchBrowserEvent('close-banner-embed-modal');
        $this->emit('refreshBannerGroupTable');
        $this->dispatchBrowserEvent('flash-message', ['message' => 'Banners embedded successfully!', 'type' => 'success']);
    }

    public function render()
    {
        return view('livewire.backend.banner.embed');
    }
}
