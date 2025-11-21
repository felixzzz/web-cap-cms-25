<?php

namespace App\Http\Livewire\Backend\Form;

use App\Http\Livewire\Backend\TableStyleHelper;
use App\Models\Form\Field;
use App\Models\Form\Form;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;

class FieldList extends DataTableComponent
{

    protected $model = Field::class;

    public $status;

    public $form;
    /**
     * @param string $status
     */
    public function mount($status = 'publish', $form): void
    {
        $this->form = $form;
    }

    public function builder(): Builder
    {
        $query = Field::query()->where('form_id', $this->form);
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
            Column::make('input')->hideIf(true),
            Column::make('name')->hideIf(true),
            Column::make('is_required')->hideIf(true),
            Column::make('Sort')->format(function ($row) {
                return $row;
            })

                ->sortable()
                ->html(),
            Column::make(__('Label'), 'label')->format(function ($row, $object) {
                $required = null;
                if ($object->is_required) {
                    $required = 'required';
                }
                return '
                    <div class="d-flex align-items-center">
                        <div class="d-flex justify-content-start flex-column">
                            <a href="#" class="text-dark fw-bold text-hover-primary fs-6 '.$required.'">'.$row.'</a>
                            <span class="text-muted fw-semibold text-muted d-block fs-7">'.$object->name.'</span>
                        </div>
                    </div>
                ';
            })->html()
                ->sortable()
                ->searchable(),
            Column::make('Type', 'type')->format(function ($row, $object) {
                if ($row === 'input') {
                    return ucwords($row). ' - '.ucwords($object->input);
                } else {
                    return ucwords($row);
                }
            })
                ->sortable()
                ->eagerLoadRelations()
                ->searchable(),
            BooleanColumn::make('Is Required')
                ->sortable()
                ->collapseOnMobile(),
            Column::make('Created At')->format(function ($row) {
                return $row->format('Y-m-d H:i:s');
            })
                ->searchable()
                ->sortable()
                ->eagerLoadRelations(),
            Column::make(__('Actions'))->label(
                fn($row, Column $column) => view(
                    'backend.form.fields.includes.actions',
                    [
                        'model' => $row,
                        'status' => $this->status,
                        'permission' => "admin.access.form.fields",
                        'route' => 'admin.form.field',
                    ]
                )
            )->html(),

        ];
    }

    public function reorder($items)
    {
        foreach ($items as $item) {
            $model = Field::where('id', $item['value'])->first();
            if ($model) {
                $formId = $model->form->id;
                $anotherModel = Field::where('sort', $item['order'])->where('form_id', $formId)->first();
                if ($anotherModel) {
                    Field::swapOrder($model, $anotherModel);
                }
            }
        }
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setReorderEnabled();
        TableStyleHelper::setTableStyle($this);
    }
}
