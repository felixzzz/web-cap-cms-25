@extends('backend.layouts.app')

@section('title', 'Post Types')

@section('content')
    <x-backend.card>
        <x-slot name="header">
            Post Types
        </x-slot>

        @if ($logged_in_user->hasAllAccess())
            <x-slot name="headerActions">
                <x-utils.link icon="fa fa-plus" class="btn btn-sm btn-primary me-2" :href="route('admin.posttype.create')" :text="__('Create Type')" />
                <x-utils.link icon="fa fa-trash" class="btn btn-sm btn-outline" :href="route('admin.posttype.deleted')" :text="__('Trashed')" />
            </x-slot>
        @endif

        <x-slot name="body">
            <livewire:backend.post.postype-table />
        </x-slot>
    </x-backend.card>
@endsection
