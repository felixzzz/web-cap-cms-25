<?php

namespace App\Http\Livewire\Backend;

use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

/**
 * Class UsersTable.
 */
class UsersTable extends DataTableComponent
{
    public string $tableName = 'users';
    public array $users = [];
    public string $status;

    public $columnSearch = [
        'name' => null,
        'email' => null,
    ];

    public function mount(string $status = 'active'): void
    {
        $this->status = $status;
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable()
                ->setSortingPillTitle('Key')
                ->setSortingPillDirections('0-9', '9-0')
                ->html(),
            Column::make(__('Name'))
                ->sortable()
                ->searchable(),
            Column::make(__('E-mail'), 'email')
                ->sortable(),
            Column::make(__('Verified'), 'email_verified_at')
                ->sortable(),
            Column::make(__('Active'), 'active')
                ->format(
                    fn ($value, $row, Column $column) => '<div class="text-center">'.($value ? 'Yes' : 'No').'</div>'
                )
                ->html()
                ->sortable(),
            Column::make(__('2FA'), 'twoFactorAuth')
                ->label(
                    fn ($row) => '<div class="text-center">'.($row->two_factor_auth_count ? 'Yes' : 'No').'</div>'
                )
                ->html(),
            Column::make(__('Roles'))
                ->label(fn ($row) => $row->roles_label),
            Column::make(__('Actions'), 'id')->format(
                fn ($value, $row, Column $column) => view('backend.auth.user.includes.actions')->withUser($row)
            )->html()
        ];
    }

    public function filters(): array
    {
        $roles = Role::where('type', 'admin')->get()->pluck('name', 'id')->toArray();
        $roles = array_merge([0 => 'Any'], $roles);

        return [
            SelectFilter::make('Role', 'role')
                ->setFilterPillTitle('Role')
                ->options($roles)
                ->filter(function(Builder $builder, string $value) {
                    if ($value != 0) {
                        $builder->role([$value]);
                    }
                }),
            SelectFilter::make('E-mail Verified', 'email_verified_at')
                ->setFilterPillTitle('Verified')
                ->options([
                    ''    => 'Any',
                    'yes' => 'Yes',
                    'no'  => 'No',
                ])
                ->filter(function(Builder $builder, string $value) {
                    if ($value === 'yes') {
                        $builder->whereNotNull('email_verified_at');
                    } elseif ($value === 'no') {
                        $builder->whereNull('email_verified_at');
                    }
                }),
        ];
    }

    public function builder(): Builder
    {
        $query = User::query()
                    ->with('roles', 'twoFactorAuth')
                    ->withCount('twoFactorAuth');

        if ($this->status === 'deleted') {
            $query = $query->onlyTrashed();
        } elseif ($this->status === 'deactivated') {
            $query = $query->onlyDeactivated();
        } else {
            $query = $query->onlyActive();
        }

        $query = $query->when($this->columnSearch['name'] ?? null, fn ($query, $name) => $query->where('users.name', 'like', '%' . $name . '%'))
                        ->when($this->columnSearch['email'] ?? null, fn ($query, $email) => $query->where('users.email', 'like', '%' . $email . '%'));

        return $query;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        TableStyleHelper::setTableStyle($this);
    }
}
