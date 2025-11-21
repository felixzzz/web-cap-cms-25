<?php

namespace App\Http\Livewire\Backend;

use App\Domains\Auth\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

/**
 * Class RolesTable.
 */
class RolesTable extends DataTableComponent
{
    const ENABLE_USER_TYPE_NON_ADMIN_KEY = 'app.auth_enable_user_type_non_admin';

    /**
     * @return Builder
     */
    public function builder(): Builder
    {
        return Role::with('permissions:id,name,description')
            ->withCount(['users as users_count'])
            ->when($this->getFilter('search'), fn ($query, $term) => $query->search($term));
    }

    public function getFilter($column): bool
    {
        return ! (empty($this->columnSearch[$column] ?? null));
    }

    public function columns(): array
    {
        $enable_user_type_non_admin = config(self::ENABLE_USER_TYPE_NON_ADMIN_KEY);

        return [
            Column::make(__('Type'))
                ->sortable()
                ->hideIf(!$enable_user_type_non_admin),
            Column::make(__('Name'))
                ->sortable()
                ->searchable(),
            Column::make(__('Permissions'))
                ->label(fn ($row) => $row->permissions_label),
            Column::make(__('Number of Users'), 'id') // Todo: How to select users count field
                ->format(function($value, $row) {
                    return $row->users_count.' users';
                })->html(),
            Column::make(__('Actions'), 'id')->format(
                fn ($value, $row, Column $column) => view('backend.auth.role.includes.actions')->withModel($row)
            )->html(),
        ];
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        TableStyleHelper::setTableStyle($this);
    }
}
