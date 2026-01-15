<?php

namespace App\Http\Livewire\Backend\Banner;

use App\Models\BannerGroup;
use App\Http\Livewire\Backend\TableStyleHelper;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class BannerGroupTable extends DataTableComponent
{
    protected $model = BannerGroup::class;

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
            Column::make('Created At', 'created_at')
                ->sortable(),
            Column::make('Actions')
                ->label(
                    fn($row, Column $column) => view('backend.banner.includes.actions', ['model' => $row])
                )->html(),
        ];
    }
}
