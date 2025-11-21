@extends('backend.layouts.app')

@section('title', __('View Role'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('View Role')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action me-2" :href="route('admin.role.index')" :text="__('Back')" />
            &nbsp;
            <x-utils.edit-button :href="route('admin.role.edit', $role)" />
        </x-slot>

        <x-slot name="body">
            <table class="table table-hover">
                <tr>
                    <th>@lang('Name')</th>
                    <td><b>{{ $role->name }}</b></td>
                </tr>
                <tr>
                    <th>@lang('Permissions')</th>
                    <td>
                        @foreach ($permissions as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th>@lang('Total User')</th>
                    <td>{{ $role->users->count() }} Users</td>
                </tr>


            </table>
        </x-slot>

        <x-slot name="footer">
            <small class="float-right text-muted">
                <strong>@lang('Role Created'):</strong> @displayDate($role->created_at) ({{ $role->created_at->diffForHumans() }}),
                <strong>@lang('Last Updated'):</strong> @displayDate($role->updated_at) ({{ $role->updated_at->diffForHumans() }})
            </small>
        </x-slot>
    </x-backend.card>
@endsection
