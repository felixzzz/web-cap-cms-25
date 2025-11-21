@extends('backend.layouts.app')

@section('title', __('Trashed') . 'Document')

@section('breadcrumb-links')
    @include('backend.postCategory.includes.breadcrumb-links')
@endsection

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Trashed')
        </x-slot>

        <x-slot name="body">
            <livewire:backend.document.document-table status="deleted"/>
        </x-slot>
    </x-backend.card>
@endsection
