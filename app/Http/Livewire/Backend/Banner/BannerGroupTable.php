<?php

namespace App\Http\Livewire\Backend\Banner;

use App\Models\BannerGroup;
use App\Http\Livewire\Backend\TableStyleHelper;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;


class BannerGroupTable extends DataTableComponent
{
    protected $listeners = ['refreshBannerGroupTable' => '$refresh'];

    protected $model = BannerGroup::class;

    public function builder(): Builder
    {
        return BannerGroup::query()
            ->with(['activeBanners', 'posts']);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        TableStyleHelper::setTableStyle($this);
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable(),
            Column::make('Title', 'title')
                ->searchable()
                ->sortable(),
            Column::make('Status', 'id')
                ->format(function ($value, $row, Column $column) {
                    if ($row->activeBanners->count() > 0) {
                        return '<span class="badge badge-light-success">Active</span>';
                    }
                    return '<span class="badge badge-light-warning">Draft</span>';
                })
                ->html(),
            Column::make('Embed At', 'id')
                ->format(function ($value, $row, Column $column) {
                    $count = $row->activeBanners->count();
                    if ($count > 0) {
                        return '<a href="#" wire:click.prevent="$emit(\'openBannerActiveList\', ' . $row->id . ')" class="btn btn-sm btn-secondary" style="font-size:12px;">' . $count . ' Post(s)</a>';
                    }
                    return '<span class="text-muted">-</span>';
                })
                ->html(),
            Column::make('Created At', 'created_at')
                ->format(fn($value) => Carbon::parse($value)->format('d M Y H:i'))
                ->sortable(),
            Column::make('Actions')
                ->label(
                    fn($row, Column $column) => view('backend.banner.includes.actions', ['model' => $row])
                )->html(),
        ];
    }
}
