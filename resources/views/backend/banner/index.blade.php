@extends('backend.layouts.app')

@section('title', __('Banners'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Banners')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link icon="fa fa-plus" class="btn btn-sm btn-primary" :href="route('admin.banner.create')"
                :text="__('Create Banner')" />
        </x-slot>

        <x-slot name="body">
            <livewire:backend.banner.banner-group-table />
            <livewire:backend.banner.banner-embed />
            <livewire:backend.banner.banner-active-list />
        </x-slot>
    </x-backend.card>
@endsection