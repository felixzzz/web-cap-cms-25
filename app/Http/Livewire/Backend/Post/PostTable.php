<?php

namespace App\Http\Livewire\Backend\Post;

use App\Domains\Post\Models\Post;
use App\Domains\PostCategory\Models\Category;
use App\Http\Livewire\Backend\TableStyleHelper;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class PostTable extends DataTableComponent
{
    protected $model = Post::class;

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
        $query = Post::where('posts.type', $this->post_type['type'])->select('posts.*');
        if ($this->status === 'deleted') {
            $query = $query->onlyTrashed();
        }

        $query = $query
            ->when($this->getFilter('search'), fn($query, $term) => $query->search($term));

        return $query;
    }

    public function getFilter($column): bool
    {
        return !(empty($this->columnSearch[$column] ?? null));
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
            SelectFilter::make('Category')
                ->options([
                    '' => 'All'
                ] +
                    Category::query()
                        ->orderBy('name')
                        ->get()
                        ->keyBy('id')
                        ->map(fn($cat) => $cat->name)
                        ->toArray()
                )
                ->filter(function(Builder $builder, string $value) {
                    $builder->whereHas('category', function($query) use($value) {
                        $query->where('category_id', $value);
                    });
                }),
            SelectFilter::make('Month')
                ->options([
                    '' => 'All Time',
                    '01' => 'January - '.date('Y'),
                    '02' => 'February - '.date('Y'),
                    '03' => 'March - '.date('Y'),
                    '04' => 'April - '.date('Y'),
                    '05' => 'May - '.date('Y'),
                    '06' => 'June - '.date('Y'),
                    '07' => 'July - '.date('Y'),
                    '08' => 'August - '.date('Y'),
                    '09' => 'September - '.date('Y'),
                    '10' => 'October - '.date('Y'),
                    '11' => 'November - '.date('Y'),
                    '12' => 'December - '.date('Y'),
                ])->filter(function(Builder $builder, string $value) {
                    $builder->whereMonth('posts.created_at', $value)->whereYear('posts.created_at', date('Y'));
                }),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make(__('Title'))->format(
                function ($value, $row, Column $column) {
                    return '
                    <div class="d-flex align-items-center">
                        <div class="d-flex justify-content-start flex-column">
                            <a href="'. route('admin.post.show', $row) .'" class="text-dark fw-bold text-hover-primary fs-6">' . $row->title . '</a>
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
            Column::make(__('Title En'))->format(
                    function ($value, $row, Column $column) {
                        return '
                        <div class="d-flex align-items-center">
                            <div class="d-flex justify-content-start flex-column">
                                <a href="'. route('admin.post.show', $row) .'" class="text-dark fw-bold text-hover-primary fs-6">' . $row->title_en . '</a>
                            </div>
                        </div>
                    ';
                    }
                )->html()
                    ->sortable()
                    ->searchable(),
            Column::make(__('Author'), 'user.name')
                ->format(
                    function ($value, $row, Column $column) {
                        return '<span>'.$row->user->name.' <br> '.$row->user->email.'</span>';
                    }
                )->html()
                ->sortable()
                ->eagerLoadRelations()
                ->searchable(),
            Column::make(__('Categories'), 'id')
                ->format(
                    function ($value, $row, Column $column) {
                        if(count($row->category)){
                            return '<span>'.$row->category->pluck('name')->implode(', ').'</span>';
                        }

                        return '';
                    }
                )->html()
                ->eagerLoadRelations()
                ->hideIf($this->post_type['is_category'] ? false : true),
            Column::make('Tags')
                ->label(fn($row) => $row->tags->pluck('name')->implode(', '))
                ->hideIf($this->post_type['is_tags'] ? false : true),
            Column::make(__('Status'))
                ->format(
                    function ($value, $row, Column $column) {
                        if ($row->status === 'draft') {
                            return '<span class="badge badge-light-primary">Draft</span>';
                        } else {
                            if ($row->status === 'publish') {
                                return '<span class="badge badge-light-success">Publish</span>';
                            } elseif ($row->status === 'schedule') {
                                return '<span class="badge badge-light-info">Schedule</span>';
                            } else {
                                return '<span class="badge badge-light-warning">Review</span>';
                            }
                        }
                    }
                )->html()
                ->sortable(),
            Column::make('Published At')
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
                    'backend.posts.includes.actions',
                    [
                        'model' => $row,

                        'permission' => "admin.access.{$this->post_type['type']}",
                        'route' => 'admin.post',
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

    public function reorder($items): void
    {
        foreach ($items as $item) {
            Post::find((int)$item['value'])->update(['sort' => (int)$item['order']]);
        }
    }
}
