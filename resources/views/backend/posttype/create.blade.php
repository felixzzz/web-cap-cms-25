@extends('backend.layouts.app')

@section('title', 'Create Types')

@section('content')
@php $required = '1'; $hidden = '0'; @endphp
<x-forms.post :action="route('admin.posttype.store')" enctype="multipart/form-data">
    <x-backend.card>
        <x-slot name="header">
            @lang('Create Post Type')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.posttype.index')" :text="__('Cancel')" />
        </x-slot>

        <x-slot name="body">
            <x-forms.text-input name="name" label="Post Type Name" required="{{ $required }}" placeholder="The title of post" text=""/>
            <div class="d-flex flex-stack mb-8">
                <div class="me-5">
                    <label class="fs-6 fw-semibold">Public</label>

                    <div class="fs-7 fw-semibold text-muted">This post type can be access outside cms (API)</div>
                </div>
                <label class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" value="1" name="is_public">
                </label>
            </div>
            <div class="d-flex flex-stack mb-16">
                <div class="me-5">
                    <label class="fs-6 fw-semibold">Show in menu</label>

                    <div class="fs-7 fw-semibold text-muted">Show this post in CMS as menu.</div>
                </div>
                <label class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" value="1" name="show_in_menu">
                </label>
            </div>
            <div class="d-flex flex-stack mb-8">
                <div class="me-5">
                    <label class="fs-6 fw-semibold">Featured</label>

                    <div class="fs-7 fw-semibold text-muted">This post type will have featured function.</div>
                </div>
                <label class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" value="1" name="featured">
                </label>
            </div>
            <div class="d-flex flex-stack mb-8">
                <div class="me-5">
                    <label class="fs-6 fw-semibold">Featured Image</label>

                    <div class="fs-7 fw-semibold text-muted">This post type will have featured image.</div>
                </div>
                <label class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" value="1" name="featured_image">
                </label>
            </div>
            <div class="d-flex flex-stack mb-8">
                <div class="me-5">
                    <label class="fs-6 fw-semibold">Content</label>

                    <div class="fs-7 fw-semibold text-muted">This post type will have content area.</div>
                </div>
                <label class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" value="1" name="is_content">
                </label>
            </div>
            <div class="d-flex flex-stack mb-8">
                <div class="me-5">
                    <label class="fs-6 fw-semibold">Category</label>

                    <div class="fs-7 fw-semibold text-muted">This post type will have category.</div>
                </div>
                <label class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" value="1" name="is_category">
                </label>
            </div>
            <div class="d-flex flex-stack mb-8">
                <div class="me-5">
                    <label class="fs-6 fw-semibold">Tags</label>

                    <div class="fs-7 fw-semibold text-muted">This post type will have tags.</div>
                </div>
                <label class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" value="1" name="is_tags">
                </label>
            </div>
            <button class="btn btn-primary">Create Post Type</button>
        </x-slot>
    </x-backend.card>
</x-forms.post>
@endsection
