<?php

namespace App\Http\Livewire\Backend\Banner;

use Livewire\Component;
use App\Models\BannerGroup;
use App\Models\BannerActive;
use App\Domains\Post\Models\Post;

class BannerPagesEmbed extends Component
{
    public $bannerGroupId;
    public $bannerGroupTitle;
    public $posts = [];
    public $selectedPosts = [];
    public $location = 'navbar';
    public $search = '';
    public $language = 'all';
    public $isAllSelected = false;
    public $startDate;
    public $endDate;

    public $locations = [
        'navbar' => 'Navbar',
        'footer' => 'Footer',
    ];

    protected $listeners = ['openBannerPagesEmbed' => 'openModal'];

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

    public function loadPosts()
    {
        $query = Post::where('type', 'page');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('title_en', 'like', '%' . $this->search . '%')
                    ->orWhere('slug', 'like', '%' . $this->search . '%')
                    ->orWhere('slug_en', 'like', '%' . $this->search . '%');
            });
        }

        $rawPosts = $query->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        $transformedPosts = collect();

        foreach ($rawPosts as $post) {
            // ID Language
            $transformedPosts->push([
                'id' => $post->id . '_id',
                'original_id' => $post->id,
                'title' => $post->title ?? 'No Title (ID)',
                'slug' => $post->slug,
                'type' => $post->type,
                'created_at' => $post->created_at,
                'lang' => 'id'
            ]);

            // EN Language
            $transformedPosts->push([
                'id' => $post->id . '_en',
                'original_id' => $post->id,
                'title' => $post->title_en ?? $post->title ?? 'No Title (EN)',
                'slug' => $post->slug_en ?? $post->slug,
                'type' => $post->type,
                'created_at' => $post->created_at,
                'lang' => 'en'
            ]);
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
        \Illuminate\Support\Facades\Log::info('BannerPagesEmbed: openModal called with ID: ' . $bannerGroupId);
        $this->bannerGroupId = $bannerGroupId;
        $bannerGroup = BannerGroup::find($bannerGroupId);
        $this->bannerGroupTitle = $bannerGroup ? $bannerGroup->title : '';

        $this->selectedPosts = [];
        $this->location = 'navbar';
        $this->startDate = null;
        $this->endDate = null;
        $this->dispatchBrowserEvent('open-banner-pages-embed-modal');
    }

    public function save()
    {
        $this->validate([
            'bannerGroupId' => 'required|exists:banner_groups,id',
            'selectedPosts' => 'required|array|min:1',
            'location' => 'required|in:navbar,footer',
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
                    // If new banner ends at $end, it overlaps with existing if existing starts before $end
                    // (and existing ends after $start, handled below)
                    $q->where(function ($sub) use ($end) {
                        $sub->whereNull('start_date')
                            ->orWhere('start_date', '<=', $end);
                    });
                }

                if ($start) {
                    // If new banner starts at $start, it overlaps with existing if existing ends after $start
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
                ]
            );
        }

        $this->dispatchBrowserEvent('close-banner-pages-embed-modal');
        $this->emit('refreshBannerGroupTable');
        $this->dispatchBrowserEvent('flash-message', ['message' => 'Banners embedded successfully!', 'type' => 'success']);
    }

    public function render()
    {
        return view('livewire.backend.banner.pages-embed');
    }
}
