@extends('backend.layouts.app')

@section('title', 'Tags Management')

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Tags Management')
        </x-slot>

        @if ($logged_in_user->hasAllAccess())
            <x-slot name="headerActions">
                <x-utils.link
                    icon="fa fa-plus"
                    class="btn btn-sm btn-primary"
                    :href="route('admin.tag.create')"
                    :text="__('Create Tag')"
                />
            </x-slot>
        @endif

        <x-slot name="body">
            <livewire:backend.tags-table />
        </x-slot>
    </x-backend.card>
@endsection
