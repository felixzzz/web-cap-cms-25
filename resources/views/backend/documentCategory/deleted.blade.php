@extends('backend.layouts.app')

@section('title', __('Trashed ' . ucwords($type)) . ' Category')

@section('breadcrumb-links')
    @include('backend.postCategory.includes.breadcrumb-links')
@endsection

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Trashed')
        </x-slot>

        <x-slot name="body">
            <livewire:backend.post-category.category-table :post_type="$type" status="deleted"/>
        </x-slot>
    </x-backend.card>
@endsection
