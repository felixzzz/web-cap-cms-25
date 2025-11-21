<?php

namespace App\Http\Livewire\Backend\PostCategory;

use App\Domains\PostCategory\Models\Category;
use App\Http\Livewire\Backend\TableStyleHelper;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class CategoryTable extends DataTableComponent
{
    protected $model = Category::class;

    public $post_type = '';

    public $status;

    /**
     * @param string $status
     */
    public function mount($status = 'publish'): void
    {
        $this->status = $status;
    }

    /**
     * @return Builder
     */
    public function builder(): Builder
    {
        $query = Category::where('categories.type', $this->post_type)->select('categories.*')->withCount('posts');
        if ($this->status === 'deleted') {
            $query = $query->onlyTrashed();
        }

        $query = $query->when($this->getFilter('search'), fn($query, $term) => $query->search($term));

        return $query;
    }

    public function getFilter($column): bool
    {
        return !(empty($this->columnSearch[$column] ?? null));
    }

    public function columns(): array
    {
        return [
            Column::make('Order','sort')
                ->sortable()
                ->hideIf(false),
            Column::make(__('Name'))->format(
                function ($value, $row, Column $column) {
                    return '
                    <div class="d-flex align-items-center">
                        <div class="d-flex justify-content-start flex-column">
                            <a href="'.route('admin.category.show', [$row, $this->post_type]).'" class="text-dark fw-bold text-hover-primary fs-6">' . $row->name . '</a>
                            <span class="text-muted fw-semibold text-muted d-block fs-7">' . $row->created_at->format(
                            "j F 'y G:i"
                        ) . '</span>
                        </div>
                    </div>
                ';
                }
            )->html()
                ->sortable()
                ->searchable(),
            Column::make(__('Name EN'), 'name_en')
                ->sortable()
                ->searchable(),
            Column::make(__('Created At'), 'created_at')
                ->sortable()
                ->eagerLoadRelations()
                ->searchable(),
            Column::make(__('Actions'))->label(
                fn($row, Column $column) => view(
                    'backend.postCategory.includes.actions',
                    [
                        'model' => $row,
                        'type' => $this->post_type,
                        'permission' => "admin.access.{$this->post_type}",
                        'route' => 'admin.category',
                    ]
                )
            )->html(),
            Column::make('Created At')->hideIf(true)
        ];
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        TableStyleHelper::setTableStyle($this);
        $this->setReorderEnabled();
    }
    public function reorder($items): void
    {
        foreach ($items as $item) {
            Category::find((int)$item['value'])->update(['sort' => (int)$item['order']]);
        }
    }
}
