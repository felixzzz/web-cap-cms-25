@extends('backend.layouts.app')

@section('title', __('Banner Groups'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Banner Groups')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link icon="fa fa-plus" class="btn btn-sm btn-primary" :href="'#'" :text="__('Create Banner Group')" />
        </x-slot>

        <x-slot name="body">
            <livewire:backend.banner.banner-group-table />
        </x-slot>
    </x-backend.card>
@endsection
