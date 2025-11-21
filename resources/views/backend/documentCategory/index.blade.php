@extends('backend.layouts.app')

@section('title')

@section('breadcrumb-links')
    @include('backend.documentCategory.includes.breadcrumb-links')
@endsection

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Category')
        </x-slot>
        @if (session('flash_success'))
            <div class="alert alert-success">
                {{ session('flash_success') }}
            </div>
        @endif

        {{--        @if ($logged_in_user->can('admin.access.promo-category.create'))--}}
        <x-slot name="headerActions">
            <x-utils.link
                icon="fa fa-plus"
                class="btn btn-sm btn-primary"
                :href="route('admin.document-categories.create', $template)"
                :text="__('Create Category')"
            />
        </x-slot>
        {{--        @endif--}}

        <x-slot name="body">
            @if($template == 'investor' || $template == 'sustainability')
                <livewire:backend.document.investor-table :template="$template"/>
            @else
                <livewire:backend.document.category-table :template="$template"/>
            @endif
        </x-slot>
    </x-backend.card>
@endsection
