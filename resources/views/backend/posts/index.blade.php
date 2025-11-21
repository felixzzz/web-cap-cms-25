@extends('backend.layouts.app')

@section('title', ucwords($type['name']))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang(ucwords($type['name']))
        </x-slot>
        @if (session('flash_success'))
            <div class="alert alert-success">
                {{ session('flash_success') }}
            </div>
        @endif

        {{-- @if ($logged_in_user->hasAllAccess()) --}}
            <x-slot name="headerActions">
                <x-utils.link
                    icon="fa fa-plus"
                    class="btn btn-sm btn-primary"
                    :href="route('admin.post.create', $type['type'])"
                    :text="__('Create Post')"
                />
            </x-slot>
        {{-- @endif --}}

        <x-slot name="body">
            @if($type['type'] == 'managements')
                <livewire:backend.post.management-table :post_type="$type"/>
            @elseif($type['type'] == 'products')
                <livewire:backend.post.post-product-table :post_type="$type"/>
            @else
            <livewire:backend.post.post-table :post_type="$type"/>
            @endif
        </x-slot>
    </x-backend.card>
@endsection
