<?php

namespace App\Http\Livewire\Backend\Banner;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BannerGroup;
use App\Models\BannerActive;
use App\Domains\Post\Models\Post;

class BannerPagesEmbed extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $bannerGroupId;
    public $bannerGroupTitle;
    public $posts = [];
    public $allPostIds = []; // Track all post IDs for select all functionality
    public $totalPostsCount = 0;
    public $selectedPosts = [];
    public $location = 'navbar';
    public $search = '';
    public $language = 'all';
    public $isAllSelected = false;
    public $startDate;
    public $endDate;
    public $conflictDetails = [];
    public $isHideInMobile = false;
    public $perPage = 25;

    public $locations = [
        'navbar' => 'Navbar',
        'footer' => 'Footer',
    ];

    protected $listeners = ['openBannerPagesEmbed' => 'openModal'];

    public function updatedIsAllSelected($value)
    {
        if ($value) {
            // Select ALL posts across all pages, not just current page
            $this->selectedPosts = $this->allPostIds;
        } else {
            $this->selectedPosts = [];
        }
    }

    public function updatedSelectedPosts()
    {
        // Auto-uncheck "Select All" if not all items are selected
        if ($this->isAllSelected && count($this->selectedPosts) < count($this->allPostIds)) {
            $this->isAllSelected = false;
        }
        // Auto-check "Select All" if all items are selected
        if (!$this->isAllSelected && count($this->selectedPosts) === count($this->allPostIds) && count($this->allPostIds) > 0) {
            $this->isAllSelected = true;
        }
    }

    public function updatedPerPage()
    {
        $this->resetPage();
        $this->loadPosts();
    }

    public function mount()
    {
        $this->loadPosts();
    }

    public function updatedSearch()
    {
        $this->resetPage();
        $this->loadPosts();
    }

    public function updatedLanguage()
    {
        $this->resetPage();
        $this->loadPosts();
    }

    public function loadPosts($page = null)
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

        // Get all posts for total count and select all functionality
        $allRawPosts = (clone $query)->orderBy('created_at', 'desc')->get();

        // Transform all posts to get all IDs
        $allTransformedPosts = collect();
        foreach ($allRawPosts as $post) {
            // ID Language
            $allTransformedPosts->push([
                'id' => $post->id . '_id',
                'original_id' => $post->id,
                'title' => $post->title ?? 'No Title (ID)',
                'slug' => $post->slug,
                'type' => $post->type,
                'status' => $post->status,
                'created_at' => $post->created_at,
                'lang' => 'id'
            ]);

            // EN Language
            $allTransformedPosts->push([
                'id' => $post->id . '_en',
                'original_id' => $post->id,
                'title' => $post->title_en ?? $post->title ?? 'No Title (EN)',
                'slug' => $post->slug_en ?? $post->slug,
                'type' => $post->type,
                'status' => $post->status,
                'created_at' => $post->created_at,
                'lang' => 'en'
            ]);
        }

        // Filter by language
        if ($this->language == 'en') {
            $allTransformedPosts = $allTransformedPosts->where('lang', 'en');
        } elseif ($this->language == 'id') {
            $allTransformedPosts = $allTransformedPosts->where('lang', 'id');
        }

        $allTransformedPosts = $allTransformedPosts->values();

        // Store all post IDs for select all functionality
        $this->allPostIds = $allTransformedPosts->pluck('id')->toArray();
        $this->totalPostsCount = count($this->allPostIds);

        // Paginate the transformed posts
        $currentPage = $page ?? $this->page;
        $offset = ($currentPage - 1) * $this->perPage;

        $this->posts = $allTransformedPosts->slice($offset, $this->perPage)->values()->toArray();

        // Update isAllSelected state based on current selection
        $this->isAllSelected = count($this->selectedPosts) === count($this->allPostIds) && count($this->allPostIds) > 0;
    }

    public function getTotalPages()
    {
        return ceil($this->totalPostsCount / $this->perPage);
    }

    public function getCurrentPage()
    {
        // Use internal Livewire page state
        return $this->page;
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
        $this->loadPosts($page);
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
        $this->isHideInMobile = false;
        $this->isAllSelected = false;
        $this->resetPage();
        $this->loadPosts();
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

        $this->conflictDetails = [];
        $conflictingPosts = [];
        $nonConflictingPosts = [];

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

            $conflicts = $query->with(['post', 'bannerGroup'])->get();
            if ($conflicts->count() > 0) {
                // Get post title from the first conflict
                $conflict = $conflicts->first();
                $postTitle = $conflict->post ? $conflict->post->title : 'Unknown Post ID: ' . $conflict->post_id;
                $grp = $conflict->bannerGroup ? $conflict->bannerGroup->title : 'Unknown Group';
                $startStr = $conflict->start_date ? $conflict->start_date->format('Y-m-d') : '∞';
                $endStr = $conflict->end_date ? $conflict->end_date->format('Y-m-d') : '∞';

                $this->conflictDetails[] = [
                    'id' => $compositeId,
                    'label' => "{$postTitle} ({$lang}): {$grp} ({$startStr} to {$endStr})"
                ];
                $conflictingPosts[] = $compositeId;
            } else {
                $nonConflictingPosts[] = $compositeId;
            }
        }

        if (count($this->conflictDetails) > 0) {
            \Illuminate\Support\Facades\Log::info('Conflict Details:', $this->conflictDetails);
            $this->dispatchBrowserEvent('swal:confirm-overlap', [
                'details' => $this->conflictDetails,
                'nonConflictingPosts' => $nonConflictingPosts,
            ]);
            return;
        }

        $this->performSave();
    }

    public function forceSave($selectedConflictPosts = [])
    {
        // Merge non-conflicting posts with selected conflict posts
        $postsToSave = array_merge(
            array_filter($this->selectedPosts, function ($id) {
                return !in_array($id, array_column($this->conflictDetails, 'id'));
            }),
            $selectedConflictPosts
        );

        $this->performSave(false, $postsToSave);
    }

    public function performSave($deleteConflicts = false, $postsToSave = null)
    {
        $posts = $postsToSave ?? $this->selectedPosts;

        foreach ($posts as $compositeId) {
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

        $this->dispatchBrowserEvent('close-banner-pages-embed-modal');
        $this->emit('refreshBannerGroupTable');
        $this->dispatchBrowserEvent('flash-message', ['message' => 'Banners embedded successfully!', 'type' => 'success']);
    }

    public function render()
    {
        return view('livewire.backend.banner.pages-embed');
    }
}
