<?php

namespace App\Http\Livewire\Backend\Form;

use App\Http\Livewire\Backend\TableStyleHelper;
use App\Models\Form\Form;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;

class FormList extends DataTableComponent
{
    protected $model = Form::class;

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
        $query = Form::query()->withCount('field')->withCount('submission');
        if ($this->status === 'deleted') {
            $query = $query->onlyTrashed();
        }
        $query = $query->when($this->getFilter('search'), fn ($query, $term) => $query->search($term));
        return $query;
    }


    public function getFilter($column): bool
    {
        return ! (empty($this->columnSearch[$column] ?? null));
    }

    public function columns(): array
    {
        return [
            Column::make('id')->hideIf(true),
            Column::make(__('Name'))->format( function ($value, $row, Column $column) {
                return '
                    <div class="d-flex align-items-center">
                        <div class="d-flex justify-content-start flex-column">
                            <a href="#" class="text-dark fw-bold text-hover-primary fs-6">'.$row->name.'</a>
                            <span class="text-muted fw-semibold text-muted d-block fs-7">'.$row->id.'</span>
                        </div>
                    </div>
                ';
            })->html()
                ->sortable()
                ->searchable(),
            BooleanColumn::make('Auto Reply')->sortable()
                ->collapseOnMobile(),
            Column::make('Total Field')
                ->label(function ($row) {
                    return $row->field_count .' Fields';
                })->sortable()->eagerLoadRelations()->searchable(),
            Column::make('Submission')
                ->label(function ($row) {
                    return $row->submission_count .' Submitted';
                })->sortable()->eagerLoadRelations()->searchable(),
            Column::make('Created At')
                ->searchable()
                ->sortable()
                ->html(),
            Column::make(__('Actions'))->label(
                fn($row, Column $column) => view(
                    'backend.form.includes.actions',
                    [
                        'model' => $row,
                        'status' => $this->status,
                        'permission' => "admin.access.form",
                        'route' => 'admin.form',
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
    }
}
