<?php

namespace App\Http\Livewire\Backend\Banner;

use Livewire\Component;
use App\Models\BannerGroup;
use App\Models\BannerActive;

class BannerActiveList extends Component
{
    public $bannerGroupId;
    public $bannerGroupTitle;
    public $activeBanners = [];
    public $selected = [];
    public $selectAll = false;

    protected $listeners = ['openBannerActiveList' => 'openModal'];

    public function openModal($bannerGroupId)
    {
        $this->bannerGroupId = $bannerGroupId;
        $bannerGroup = BannerGroup::find($bannerGroupId);
        $this->bannerGroupTitle = $bannerGroup ? $bannerGroup->title : '';

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

    public function render()
    {
        return view('livewire.backend.banner.active-list');
    }
}
