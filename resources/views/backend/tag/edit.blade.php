@extends('backend.layouts.app')

@section('title', __('Update '.$heading))

@section('content')
    <x-forms.patch :action="route('admin.tag.update', $model)" class="card mb-5 mb-xxl-8">
        <x-backend.card>
            <x-slot name="header">
                @lang('Update '.$heading)
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="btn btn-sm btn-light" :href="route('admin.tag.index')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                @include('backend.tag.form')
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Update')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.patch>
@endsection
