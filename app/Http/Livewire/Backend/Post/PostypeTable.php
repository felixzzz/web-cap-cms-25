<?php

namespace App\Http\Livewire\Backend\Post;

use App\Domains\Post\Models\Post;
use App\Domains\Post\Models\PostType;
use App\Http\Livewire\Backend\TableStyleHelper;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PostypeTable extends DataTableComponent
{
    protected $model = PostType::class;

    public $status;

    /**
     * @param string $status
     */
    public function mount($status = 'publish'): void
    {
        $this->status = $status;
    }

    public function builder(): Builder
    {
        $query = PostType::query();
        if ($this->status === 'deleted') {
            $query = $query->onlyTrashed();
        }
        $query = $query
            ->when($this->getAppliedFilterWithValue('search'), fn($query, $term) => $query->search($term));

        return $query;
    }

    public function columns(): array
    {
        return [
            Column::make('id')->hideIf(true),
            Column::make('Order', 'sort')
                ->sortable(),
            Column::make(__('Name'))->format( function ($value, $row, Column $column) {
                return '
                    <div class="d-flex align-items-center">
                        <div class="d-flex justify-content-start flex-column">
                            <a href="#" class="text-dark fw-bold text-hover-primary fs-6">'.$row->name.'</a>
                            <span class="text-muted fw-semibold text-muted d-block fs-7">'.$row->created_at->format("j F 'y G:i").'</span>
                        </div>
                    </div>
                ';
            })->html()
                ->sortable()
                ->searchable(),
            Column::make('Slug')
                ->sortable()
                ->hideIf(false),
            Column::make('Created At')
                ->sortable()
                ->hideIf(false),
            Column::make('Updated At')
                ->sortable()
                ->hideIf(false),
            Column::make(__('Actions'))->label(
                fn($row, Column $column) => view(
                    'backend.posttype.includes.actions',
                    [
                        'status' => $this->status,
                        'model' => $row,
                        'route' => 'admin.posttype',
                    ]
                )
            )->html(),
        ];
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        TableStyleHelper::setTableStyle($this);
        $this->setReorderEnabled();
    }

    public function reorder($items)
    {
        foreach ($items as $item) {
            $model = PostType::where('id', $item['value'])->first();
            if ($model) {
                $anotherModel = PostType::where('sort', $item['order'])->first();
                if ($anotherModel) {
                    PostType::swapOrder($model, $anotherModel);
                }
            }
        }
    }
}
