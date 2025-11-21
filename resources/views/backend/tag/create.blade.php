@extends('backend.layouts.app')

@section('title', __('Create '.$heading))

@section('content')
    <x-forms.post :action="route('admin.tag.store')">
        <x-backend.card>
            <x-slot name="header">
                @lang('Create '.$heading)
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.tag.index')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                @include('backend.tag.form')
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Create')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection
