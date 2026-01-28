<?php

namespace App\Http\Livewire\Backend\Banner;

use Livewire\Component;
use App\Models\BannerGroup;
use App\Models\BannerActive;
use App\Domains\Post\Models\Post;

class BannerActiveList extends Component
{
    public $bannerGroupId;
    public $bannerGroupTitle;
    public $position = 'article';
    public $activeBanners = [];
    public $selected = [];
    public $selectAll = false;

    // Editing State
    public $isEditing = false;
    public $editingId = null;
    public $editingStartDate;
    public $editingEndDate;
    public $editingLocation;
    public $editingLanguage;
    public $editingHideInMobile = false;
    public $editingPostId;
    public $posts = [];
    public $search = '';
    public $filterLang = 'all';
    public $filterType = 'all';

    protected $listeners = ['openBannerActiveList' => 'openModal'];

    public function openModal($bannerGroupId)
    {
        $this->bannerGroupId = $bannerGroupId;
        $bannerGroup = BannerGroup::find($bannerGroupId);
        $this->bannerGroupTitle = $bannerGroup ? $bannerGroup->title : '';

        // Reset editing state on open
        $this->cancelEdit();

        $this->loadActiveBanners();

        $this->dispatchBrowserEvent('open-banner-active-list-modal');
    }

    public function updatedSearch()
    {
        $this->loadPosts();
    }

    public function updatedFilterLang()
    {
        $this->loadPosts();
    }

    public function updatedFilterType()
    {
        $this->loadPosts();
    }

    public function loadPosts()
    {
        $query = Post::query();

        if ($this->position == 'pages' || $this->position == 'home') {
            $query->where('type', 'page');
        } else {
            if ($this->filterType != 'all') {
                $query->where('type', $this->filterType);
            } else {
                $query->whereIn('type', ['article', 'blog', 'news']);
            }
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('title_en', 'like', '%' . $this->search . '%')
                    ->orWhere('slug', 'like', '%' . $this->search . '%')
                    ->orWhere('slug_en', 'like', '%' . $this->search . '%');
            });
        }

        $rawPosts = $query->orderBy('created_at', 'desc')->limit(50)->get();

        $transformedPosts = collect();

        foreach ($rawPosts as $post) {
            // ID Language
            if ($this->filterLang == 'all' || $this->filterLang == 'id') {
                $transformedPosts->push([
                    'id' => $post->id . '_id', // Composite ID
                    'original_id' => $post->id,
                    'title' => $post->title ?? 'No Title (ID)',
                    'slug' => $post->slug,
                    'type' => $post->type,
                    'lang' => 'id',
                    'created_at' => $post->created_at,
                ]);
            }
            // EN Language
            if ($this->filterLang == 'all' || $this->filterLang == 'en') {
                $transformedPosts->push([
                    'id' => $post->id . '_en', // Composite ID
                    'original_id' => $post->id,
                    'title' => $post->title_en ?? $post->title ?? 'No Title (EN)',
                    'slug' => $post->slug_en ?? $post->slug,
                    'type' => $post->type,
                    'lang' => 'en',
                    'created_at' => $post->created_at,
                ]);
            }
        }

        $this->posts = $transformedPosts->toArray();
    }


    public function loadActiveBanners()
    {
        $this->activeBanners = BannerActive::where('banner_group_id', $this->bannerGroupId)
            ->with('post')
            ->orderBy('created_at', 'desc')
            ->get();

        $this->selected = [];
        $this->selectAll = false;
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->activeBanners->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function delete($id)
    {
        $banner = BannerActive::find($id);
        if ($banner) {
            $banner->delete();
            $this->loadActiveBanners();
            $this->emit('refreshBannerGroupTable');
            $this->dispatchBrowserEvent('flash-message', ['message' => 'Banner removed successfully!', 'type' => 'success']);
        }
    }

    public function deleteSelected()
    {
        if (count($this->selected) > 0) {
            BannerActive::whereIn('id', $this->selected)->delete();
            $this->selected = [];
            $this->selectAll = false;
            $this->loadActiveBanners();
            $this->emit('refreshBannerGroupTable');
            $this->dispatchBrowserEvent('flash-message', ['message' => 'Selected banners removed successfully!', 'type' => 'success']);
        }
    }

    public function edit($id)
    {
        $banner = BannerActive::find($id);
        if ($banner) {
            $this->editingId = $banner->id;
            $this->editingStartDate = $banner->start_date ? $banner->start_date->format('Y-m-d\TH:i') : null;
            $this->editingEndDate = $banner->end_date ? $banner->end_date->format('Y-m-d\TH:i') : null;
            $this->editingLocation = $banner->location;
            // $this->editingLanguage = $banner->language; // Removed, derived from post selection
            $this->editingHideInMobile = $banner->is_hide_in_mobile;

            // Set composite ID
            $lang = $banner->language ?? 'id';
            $this->editingPostId = $banner->post_id . '_' . $lang;

            $this->isEditing = true;
            $this->loadPosts(); // Load posts when entering edit mode

            // If the current post is NOT in the top 20 loaded, we should fetch it and add it
            // so it appears selected. Ideally.
            // For now, let's rely on search or the user re-selecting if they want to change it.
            // But to show the "current" selection correctly, it must be in $this->posts.

            // Quick fix to ensure current is in list:
            if (!collect($this->posts)->contains('id', $this->editingPostId)) {
                // Fetch specific post
                $currentPost = Post::find($banner->post_id);
                if ($currentPost) {
                    // Manually append
                    if ($lang == 'id' && $currentPost->title) {
                        array_unshift($this->posts, [
                            'id' => $currentPost->id . '_id',
                            'original_id' => $currentPost->id,
                            'title' => $currentPost->title,
                            'type' => $currentPost->type,
                            'lang' => 'id',
                            'created_at' => $currentPost->created_at,
                        ]);
                    } elseif ($lang == 'en' && $currentPost->title_en) {
                        array_unshift($this->posts, [
                            'id' => $currentPost->id . '_en',
                            'original_id' => $currentPost->id,
                            'title' => $currentPost->title_en,
                            'type' => $currentPost->type,
                            'lang' => 'en',
                            'created_at' => $currentPost->created_at,
                        ]);
                    }
                }
            }
        }
    }

    public function cancelEdit()
    {
        $this->isEditing = false;
        $this->editingId = null;
        $this->editingStartDate = null;
        $this->editingEndDate = null;
        $this->editingLocation = null;
        $this->editingHideInMobile = false;
        $this->editingPostId = null;
        $this->posts = [];
        $this->search = '';
    }

    public function update()
    {
        $this->validate([
            'editingLocation' => 'required|string',
            'editingStartDate' => 'nullable|date',
            'editingEndDate' => 'nullable|date|after_or_equal:editingStartDate',
            'editingPostId' => 'required', // String composite ID
        ]);

        $parts = explode('_', $this->editingPostId);
        if (count($parts) < 2) {
            $this->addError('editingPostId', 'Invalid selection');
            return;
        }

        $postId = $parts[0];
        $lang = $parts[1];

        $banner = BannerActive::find($this->editingId);
        if ($banner) {
            // Auto-reset hide in mobile if location is not side
            if (!in_array($this->editingLocation, ['left', 'right'])) {
                $this->editingHideInMobile = false;
            }

            $banner->update([
                'location' => $this->editingLocation,
                'start_date' => $this->editingStartDate,
                'end_date' => $this->editingEndDate,
                'language' => $lang,
                'is_hide_in_mobile' => $this->editingHideInMobile,
                'post_id' => $postId,
            ]);

            $this->cancelEdit();
            $this->loadActiveBanners();
            $this->emit('refreshBannerGroupTable');
            $this->dispatchBrowserEvent('flash-message', ['message' => 'Banner updated successfully!', 'type' => 'success']);
        }
    }

    public function render()
    {
        return view('livewire.backend.banner.active-list');
    }
}
