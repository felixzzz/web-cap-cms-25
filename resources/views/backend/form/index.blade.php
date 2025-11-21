@extends('backend.layouts.app')

@section('title', __('Forms'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Forms Management')
        </x-slot>

        @if ($logged_in_user->can('admin.access.forms.create'))
            <x-slot name="headerActions">
                <x-utils.link
                    icon="fa fa-plus"
                    class="btn btn-sm btn-primary"
                    :href="route('admin.form.create')"
                    :text="__('Create Form')"
                />
            </x-slot>
        @endif

        <x-slot name="body">
            <livewire:backend.form.form-list />
        </x-slot>
    </x-backend.card>
@endsection
