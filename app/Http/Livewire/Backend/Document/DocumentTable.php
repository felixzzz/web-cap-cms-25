<?php

namespace App\Http\Livewire\Backend\Document;

use App\Domains\Document\Models\Document;
use App\Domains\Document\Models\DocumentCategory;
use App\Http\Livewire\Backend\TableStyleHelper;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class DocumentTable extends DataTableComponent
{
    protected $model = Document::class;

    public $template = '';

    public $status;
    public $pages = [];
    public $selectedPage = '';

    /**
     * @param string $status
     */
    public function mount($status = 'publish'): void
    {
        $this->status = $status;
        $path = 'templates/pages.json';
        if (file_exists(resource_path($path))) {
            $fileJson = file_get_contents(resource_path($path));
            $this->pages = json_decode($fileJson, true);
        }
    }

    /**
     * @return Builder
     */
    public function builder(): Builder
    {   
        $query = Document::select('documents.*')->with('category');
        if($this->template){
            $query->where('page',$this->template);
        }
        if ($this->status === 'deleted') {
            $query = $query->onlyTrashed();
        }

        $query = $query->when($this->getFilter('search'), fn($query, $term) => $query->search($term));

        return $query->orderBy('id','DESC');
    }
    public function getFilter($column): bool
    {
        return !(empty($this->columnSearch[$column] ?? null));
    }

    public function filters(): array
    {
        $pageOptions = array_reduce(
            $this->pages,
            function ($carry, $page) {
                if (isset($page['name']) && !empty($page['name'])) {
                    $carry[$page['name']] = $page['label'];
                }
                return $carry;
            },
            ['' => 'All']
        );

        return [
            SelectFilter::make('Category')
                ->options([
                    '' => 'All'
                ] +
                    DocumentCategory::query()
                        ->where('page',$this->template)
                        ->orderBy('name_id')
                        ->get()
                        ->keyBy('id')
                        ->map(fn($cat) => $cat->name_id)
                        ->toArray()
                )
                ->filter(function(Builder $builder, string $value) {
                    $builder->where('category_id', $value);
                }),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make(__('Categories'), 'id')
                ->format(
                    function ($value, $row, Column $column) {
                        if($row->category_id){
                            return '<span>'.DocumentCategory::where('id',$row->category_id)->pluck('name_id')->first().'</span>';
                        }

                        return '';
                    }
                )->html()
                ->eagerLoadRelations(),
            Column::make(__('Name'), 'document_name_id')->format(
                function ($value, $row, Column $column) {
                    return '
                    <div class="d-flex align-items-center">
                        <div class="d-flex justify-content-start flex-column">
                            <a href="'.route('admin.document.edit', ['document' => $row, 'template' => $this->template]).'" class="text-dark fw-bold text-hover-primary fs-6">' . $row->document_name_id . '</a>
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
            Column::make(__('Name EN'), 'document_name_en')
                ->sortable()
                ->searchable(),
            Column::make(__('File ID'), 'document_file_id')->format(
                    function ($value, $row, Column $column) {
                        if ($row->document_file_id) {
                            return '
                            <div class="d-flex align-items-center">
                                <div class="d-flex justify-content-start flex-column">
                                    <a href="'.config('filesystems.disks.s3.url').'/'  . $row->document_file_id . '" class="text-blue fw-bold text-hover-primary fs-6" target="_blank">Link File</a>
                                </div>
                            </div>
                            ';
                        } else {
                            return '-';
                        }
                    }
                )->html()
                    ->sortable()
                    ->searchable(),
                    Column::make(__('File EN'), 'document_file_en')->format(
                        function ($value, $row, Column $column) {
                            // Check if the document_file_en exists
                            if ($row->document_file_en) {
                                return '
                                <div class="d-flex align-items-center">
                                    <div class="d-flex justify-content-start flex-column">
                                        <a href="'.config('filesystems.disks.s3.url').'/' . $row->document_file_en . '" class="text-blue fw-bold text-hover-primary fs-6" target="_blank">Link File</a>
                                    </div>
                                </div>
                                ';
                            } else {
                                return '-';
                            }
                        }
                    )->html(),
            Column::make(__('Categories'), 'category_id')
                    ->format(
                        function ($value, $row, Column $column) {
                            if($row->category_id){
                                return '<span>'.DocumentCategory::where('id',$row->category_id)->value('name_id').'</span>';
                            }
    
                            return '';
                        }
                    )->html()
                    ->eagerLoadRelations(),
            Column::make(__('Publish At'), 'published_at')
                ->sortable()
                ->eagerLoadRelations()
                ->searchable(),
            Column::make(__('Actions'))->label(
                fn($row, Column $column) => view(
                    'backend.document.includes.actions',
                    [
                        'model' => $row,
                        'template' => $this->template,
                        'permission' => "admin.access.{$this->template}",
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
    }
    public function reorder($items): void
    {
        foreach ($items as $item) {
            Document::find((int)$item['value'])->update(['sort' => (int)$item['order']]);
        }
    }

    public function pageFilter(): array
    {
        // Build options from JSON data
        $options = [
            '' => 'All', // Default option
        ] + array_filter(
            array_map(
                fn($page) => $page['label'],
                $this->pages
            ),
            fn($label) => $label !== null
        );

        return [
            'options' => $options,
            'filter' => function (Builder $builder, string $value) {
                if ($value !== '') {
                    $builder->where('page', $value);
                }
            }
        ];
    }

}
