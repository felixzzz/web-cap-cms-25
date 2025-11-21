<?php

namespace App\Http\Livewire\Backend\Form;

use App\Http\Livewire\Backend\TableStyleHelper;
use App\Models\Form\Submission;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class SubmissionTable extends DataTableComponent
{
    protected $model = Submission::class;

    public $form;

    public function mount($form): void
    {
        $this->form = $form;
    }

    public function builder(): Builder
    {
        $query = Submission::query()->where('form_id', $this->form);
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
            Column::make('Created At')
                ->searchable()
                ->sortable()
                ->html(),
            Column::make(__('Actions'))->label(
                fn($row, Column $column) => view(
                    'backend.form.submission.includes.actions',
                    [
                        'model' => $row,
                        'form' => $this->form,
                        'permission' => "admin.access.page",
                        'route' => 'admin.form.submission',
                    ]
                )
            )->html(),
        ];
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('created_at', 'desc');
        TableStyleHelper::setTableStyle($this);
    }
}
