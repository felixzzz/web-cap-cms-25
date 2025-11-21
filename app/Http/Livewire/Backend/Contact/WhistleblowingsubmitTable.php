<?php

namespace App\Http\Livewire\Backend\Contact;

use App\Domains\Post\Models\ContactUs;
use App\Http\Livewire\Backend\TableStyleHelper;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;use 
App\Domains\PostCategory\Models\Category;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class WhistleblowingsubmitTable extends DataTableComponent
{
    protected $model = ContactUs::class;
    public $post_type = '';
    public $status;

    public function mount($status = 'publish'): void
    {
        $this->status = $status;
    }

    public function builder(): Builder
    {
        $query = ContactUs::select('*')->where('type','Whistleblowing');

        $query = $query->when($this->getFilter('search'), fn($query, $term) => $query->search($term));

        return $query->orderBy('id', 'DESC');
    }

    public function getFilter($column): bool
    {
        return !(empty($this->columnSearch[$column] ?? null));
    }
    public function filters(): array
    {
        return [
            SelectFilter::make('Topic')
                ->options([
                    '' => 'All'
                ] +
                    Category::query()
                        ->where('type','whistleblowing')
                        ->orderBy('name')
                        ->get()
                        ->keyBy('id')
                        ->map(fn($cat) => $cat->name)
                        ->toArray()
                )
                ->filter(function(Builder $builder, string $value) {
                    $builder->where('topic_id', $value);
            }),
        ];
    }

    public function columns(): array
    {
        return [        
            Column::make(__('Type'), 'type')->format(
                fn($value, $row) => ucfirst($value)
            )->sortable()->eagerLoadRelations()->searchable(),
            Column::make(__('Topic'), 'topic_id')->format(
                fn($value, $row) => $row->topic ? ucfirst($row->topic->name) : 'N/A'
            )->sortable()->eagerLoadRelations()->searchable(),
            Column::make(__('Name'),'firstname')->format(
                fn($value, $row) => '
                    <div class="d-flex align-items-center">
                        <div class="d-flex justify-content-start flex-column">
                            <a href="'.route('admin.contact.show',[$row]).'" class="text-dark fw-bold text-hover-primary fs-6">' . $row->firstname . ' ' . $row->lastname . '</a>
                            <span class="text-muted fw-semibold text-muted d-block fs-7">' . $row->created_at->format("j F 'y G:i") . '</span>
                        </div>
                    </div>')->html()->sortable()->searchable(),
            Column::make(__('Email'), 'email')->sortable()->eagerLoadRelations()->searchable(),
            Column::make(__('Country'), 'country')->sortable()->eagerLoadRelations()->searchable(),
            Column::make(__('Created At'), 'created_at')->sortable()->eagerLoadRelations()->searchable(),
            Column::make(__('Actions'))->label(
                fn($row) => view(
                    'backend.contact.whistleblowing.actions',
                    [
                        'model' => $row,
                        'type' => $this->post_type,
                        'permission' => "admin.access.{$this->post_type}",
                        'route' => 'admin.whistleblowing',
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

