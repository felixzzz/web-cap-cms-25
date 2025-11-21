@extends('backend.layouts.app')

@section('title', 'Document')

@section('breadcrumb-links')
    @include('backend.document.includes.breadcrumb-links')
@endsection

@section('content')
    <x-backend.card>
        <x-slot name="header">
            Document {{ ucfirst(str_replace('_', ' ', $template)) }}
        </x-slot>

        {{--        @if ($logged_in_user->can('admin.access.promo-category.create'))--}}
        <x-slot name="headerActions">
            <x-utils.link
                icon="fa fa-plus"
                class="btn btn-sm btn-primary"
                :href="route('admin.document.create', $template)"
                :text="__('Create Document')"
            />
        </x-slot>
        {{--        @endif--}}

        <x-slot name="body">
            @if($template == 'about_who_we_are')
                <livewire:backend.document.document-about-table :template="$template"/>
            @else
                <livewire:backend.document.document-table :template="$template"/>
            @endif
        </x-slot>
    </x-backend.card>
@endsection
