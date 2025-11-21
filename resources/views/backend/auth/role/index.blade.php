@extends('backend.layouts.app')

@section('title', __('Role Management'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Role Management')
        </x-slot>

        @if ($logged_in_user->hasAllAccess())
            <x-slot name="headerActions">
                <x-utils.link
                    icon="fa fa-plus"
                    class="btn btn-sm btn-primary"
                    :href="route('admin.role.create')"
                    :text="__('Create Role')"
                />
            </x-slot>
        @endif

        <x-slot name="body">
            <livewire:backend.roles-table/>
        </x-slot>
    </x-backend.card>
@endsection
