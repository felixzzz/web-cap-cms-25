<?php

namespace App\Http\Livewire\Backend\Banner;

use Livewire\Component;
use App\Models\BannerGroup;
use App\Models\BannerActive;

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

    public function updatedSelected()
    {
        $this->selectAll = count($this->selected) === $this->activeBanners->count();
    }

    public function deleteSelected()
    {
        if (count($this->selected) > 0) {
            BannerActive::whereIn('id', $this->selected)->delete();
            $this->loadActiveBanners();
            $this->emit('refreshBannerGroupTable');
            $this->dispatchBrowserEvent('flash-message', ['message' => 'Selected banners removed successfully!', 'type' => 'success']);
        }
    }

    public function delete($id)
    {
        $bannerActive = BannerActive::find($id);
        if ($bannerActive) {
            $bannerActive->delete();
            $this->loadActiveBanners();
            $this->emit('refreshBannerGroupTable');
            $this->dispatchBrowserEvent('flash-message', ['message' => 'Banner removed successfully!', 'type' => 'success']);
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
            $this->editingLanguage = $banner->language ?? (($this->position == 'article') ? 'id' : 'id'); // Default or existing
            $this->isEditing = true;
        }
    }

    public function cancelEdit()
    {
        $this->isEditing = false;
        $this->editingId = null;
        $this->editingStartDate = null;
        $this->editingEndDate = null;
        $this->editingLocation = null;
    }

    public function update()
    {
        $this->validate([
            'editingLocation' => 'required|string',
            'editingStartDate' => 'nullable|date',
            'editingEndDate' => 'nullable|date|after_or_equal:editingStartDate',
        ]);

        $banner = BannerActive::find($this->editingId);
        if ($banner) {
            $banner->update([
                'location' => $this->editingLocation,
                'start_date' => $this->editingStartDate,
                'end_date' => $this->editingEndDate,
                'language' => $this->editingLanguage,
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
