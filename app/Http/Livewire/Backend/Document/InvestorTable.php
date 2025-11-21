<?php

namespace App\Http\Livewire\Backend\Document;

use App\Domains\Document\Models\DocumentCategory;
use App\Http\Livewire\Backend\TableStyleHelper;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class InvestorTable extends DataTableComponent
{
    protected $model = DocumentCategory::class;

    public $post_type = '';
    public $template = '';

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
        $query = DocumentCategory::select('*')->withCount('documents');
        if($this->template){
            $query->where('page','like','%'.$this->template.'%');
        }
        if ($this->status === 'deleted') {
            $query = $query->onlyTrashed();
        }

        $query = $query->when($this->getFilter('search'), fn($query, $term) => $query->search($term));

        return $query->orderBy('sort','ASC');
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
            Column::make(__('Page'))->format(
                function ($value, $row, Column $column) {
                    // Format the 'page' column value
                    return ucwords(str_replace('_', ' ', $row->page));
                }
            )->sortable()
            ->searchable(),
            Column::make(__('Name'),'name_id')->format(
                function ($value, $row, Column $column) {
                    return '
                    <div class="d-flex align-items-center">
                        <div class="d-flex justify-content-start flex-column">
                            <a href="'.route('admin.document-categories.show', [$row]).'" class="text-dark fw-bold text-hover-primary fs-6">' . $row->name_id . '</a>
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
            Column::make('Total Documents')
                ->label(function ($row) {
                    return $row->documents_count .' Docs';
                })->sortable()->eagerLoadRelations()->searchable(),
            Column::make(__('Created At'), 'created_at')
                ->sortable()
                ->eagerLoadRelations()
                ->searchable(),
            Column::make(__('Actions'))->label(
                fn($row, Column $column) => view(
                    'backend.documentCategory.includes.actions',
                    [
                        'model' => $row,
                        'template' => $this->template,
                        'type' => $this->post_type,
                        'permission' => "admin.access.{$this->post_type}",
                        'route' => 'admin.document',
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
            DocumentCategory::find((int)$item['value'])->update(['sort' => (int)$item['order']]);
        }
    }
}
