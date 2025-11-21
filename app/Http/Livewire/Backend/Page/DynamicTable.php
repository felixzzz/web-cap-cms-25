<?php

namespace App\Http\Livewire\Backend\Page;

use App\Domains\Post\Models\Post;
use App\Domains\PostCategory\Models\Category;
use App\Http\Livewire\Backend\TableStyleHelper;
use App\Models\Extra\Meta;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\NumberFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class DynamicTable extends DataTableComponent
{
    protected $model = Post::class;
    public $status;

    public ?string $defaultSortColumn = 'sort';

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
        $query = Post::query()
            ->select('posts.*')
            ->leftJoin('posts as parent_posts', 'posts.parent', '=', 'parent_posts.id')
            ->where('posts.type', 'page')
            ->where('posts.pages_dynamic', 'yes');

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
            Column::make('Order','sort')
                ->sortable()
                ->hideIf(false),
            Column::make(__('Title'))->format( function ($value, $row, Column $column) {
                return '
                    <div class="d-flex align-items-center">
                        <div class="d-flex justify-content-start flex-column">
                            <a href="#" class="text-dark fw-bold text-hover-primary fs-6">'.$row->title.'</a>
                            <span class="text-muted fw-semibold text-muted d-block fs-7">'.$row->created_at->format("j F 'y G:i").'</span>
                        </div>
                    </div>
                ';
            })->html()
                ->sortable()
                ->searchable(),
            Column::make(__('Title (EN)'), 'title_en')->format( function ($value, $row, Column $column) {
                    return '
                        <div class="d-flex align-items-center">
                            <div class="d-flex justify-content-start flex-column">
                                <a href="#" class="text-dark fw-bold text-hover-primary fs-6">'.$row->title_en.'</a>
                                <span class="text-muted fw-semibold text-muted d-block fs-7">'.$row->created_at->format("j F 'y G:i").'</span>
                            </div>
                        </div>
                    ';
                })->html()
                    ->sortable()
                    ->searchable(),
            Column::make(__('Template'), 'id')
                ->format(function ($value, $row, Column $column) {
                    $template = $this->getMeta('template', $row->id);
                    $theme = $this->page_templates()->where('name', $template)->first();

                    return '
                        <div class="d-flex align-items-center">
                            <div class="d-flex justify-content-start flex-column">
                                <span  class="text-dark fw-bold text-hover-primary fs-6">'.$theme['label'].'</span>
                            </div>
                        </div>
                    ';
                })->html(),
            Column::make(__('Created By'), 'user.name')
                ->sortable()
                ->eagerLoadRelations()
                ->searchable(),
            Column::make(__('Status'))
                ->format(function ($value, $row, Column $column) {
                    if ($row->trashed()) {
                        return '<span class="badge badge-light-danger">Deleted</span>';
                    } else {
                        if ($row->status === 'draft') {
                            return '<span class="badge badge-light-primary">Draft</span>';
                        } else if ($row->status === 'publish') {
                            return '<span class="badge badge-light-success">Publish</span>';
                        } else {
                            return '<span class="badge badge-light-warning">Schedule</span>';
                        }
                    }
                })->html()
                ->sortable(),
            Column::make(__('Actions'))->label(
                fn($row, Column $column) => view(
                    'backend.dynamic.includes.actions',
                    [
                        'model' => $row,
                        'status' => $this->status,
                        'permission' => "admin.access.page",
                        'route' => 'admin.dynamic',
                    ]
                )
            )->html(),
            Column::make('Created At')->hideIf(true)
        ];
    }

    /**
     * @return \Illuminate\Support\Collection|null
     */
    public function page_templates(): ?\Illuminate\Support\Collection
    {
        $template = null;
        if (file_exists(resource_path('templates/dynamic_template.json'))) {
            $template = file_get_contents(resource_path('templates/dynamic_template.json'));
            $template = json_decode($template, true);
            $template = collect($template);
        }
        return $template;
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Status')
                ->options([
                    '' => 'All',
                    'publish' => 'Publish',
                    'draft' => 'Draft',
                    'schedule' => 'Schedule',
                ])
                ->filter(function(Builder $builder, string $value) {
                    $builder->where('status', $value);
                }),
            SelectFilter::make('Month')
                ->options([
                    '' => 'All months',
                    '01' => 'January',
                    '02' => 'February',
                    '03' => 'March',
                    '04' => 'April',
                    '05' => 'May',
                    '06' => 'June',
                    '07' => 'July',
                    '08' => 'August',
                    '09' => 'September',
                    '10' => 'October',
                    '11' => 'November',
                    '12' => 'December',
                ])->filter(function(Builder $builder, string $value) {
                    $builder->whereMonth('posts.created_at', $value);
                }),
            SelectFilter::make('Year')
                ->options([
                    '' => 'All years',
                    '2020' => '2020',
                    '2021' => '2021',
                    '2022' => '2022',
                    '2023' => '2023',
                    '2024' => '2024',

                ])->filter(function(Builder $builder, string $value) {
                    $builder->whereYear('posts.created_at', $value);
                }),
        ];
    }

    public function getMeta($key, $id)
    {
        $meta = Meta::where(['key' => $key, 'model_id' => $id])
            ->first();

        if (empty($meta->value)) {
            return null;
        }

        return $this->maybeDecodeMetaValue($meta->value);
    }

    protected function maybeDecodeMetaValue($value)
    {
        $object = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $object;
        }

        return $value;
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
            Post::find((int)$item['value'])->update(['sort' => (int)$item['order']]);
        }
    }
}
