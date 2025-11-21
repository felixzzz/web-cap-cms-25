@extends('backend.layouts.app')

@section('title', __(ucwords($type)))

@section('breadcrumb-links')
    @include('backend.postCategory.includes.breadcrumb-links')
@endsection

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Category')
        </x-slot>

        {{--        @if ($logged_in_user->can('admin.access.promo-category.create'))--}}
        <x-slot name="headerActions">
            <x-utils.link
                icon="fa fa-plus"
                class="btn btn-sm btn-primary"
                :href="route('admin.category.create', $type)"
                :text="__('Create Category')"
            />
        </x-slot>
        {{--        @endif--}}

        <x-slot name="body">
            <livewire:backend.post-category.category-table :post_type="$type"/>
        </x-slot>
    </x-backend.card>
@endsection
