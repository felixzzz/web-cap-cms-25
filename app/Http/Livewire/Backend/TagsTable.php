<?php

namespace App\Http\Livewire\Backend;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Spatie\Tags\Tag;

/**
 * Class TagsTable.
 */
class TagsTable extends DataTableComponent
{
    public string $status;

    public function builder(): Builder
    {
        $query = Tag::query()
            ->when($this->getAppliedFilterWithValue('type'), fn($query, $active) => $query->where('type', $active));

        if ($this->status === 'deleted') {
            $query = $query->onlyTrashed();
        }

        return $query;
    }

    public function mount(string $status = 'active'): void
    {
        $this->status = $status;
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
                ->sortable()
                ->setSortingPillTitle('Key')
                ->setSortingPillDirections('0-9', '9-0')
                ->html(),
            Column::make('Name', 'name')
                // ->sortable()
                ->searchable(),
            Column::make('Slug', 'slug')
                // ->sortable()
                ->searchable(),
            // Column::make('Type', 'type')
            //     ->sortable(),
            Column::make('Created At', 'created_at')
                ->sortable(),
            Column::make(__('Actions'), 'id')->format(
                fn ($value, $row, Column $column) => view('backend.tag.includes.actions')->withModel($row)
            )->html()
        ];
    }

    public function filters(): array
    {
        return [
            // SelectFilter::make('Type')
            //     ->options([
            //         '' => 'All',
            //         'article' => 'Article',
            //         'video' => 'Video',
            //     ])
            //     ->filter(function(Builder $builder, string $value) {
            //         $builder->where('type', $value);
            //     }),
        ];
    }
}
