<?php

namespace App\Domains\Post\Http\Livewire\Backend;

use App\Domains\Post\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;

class PostTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Post::with('users:id,name,email')
            ->when($this->getFilter('search'), fn ($query, $term) => $query->search($term));
    }

    public function getFilter($column): bool
    {
        return ! (empty($this->columnSearch[$column] ?? null));
    }

    public function columns(): array
    {
        return [
            Column::make(__('Title'))
                ->sortable()
                ->searchable(),
            Column::make(__('Created By'))
                ->sortable(),
            Column::make(__('Status'))
                ->sortable(),
            Column::make(__('Actions'), 'id')->format(
                fn ($value, $row, Column $column) => view('backend.posts.includes.actions')->withModel($row)
            )->html(),
        ];
    }

    public function filters(): array
    {
        return [
            DateFilter::make('Verified From'),
        ];
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }
}
