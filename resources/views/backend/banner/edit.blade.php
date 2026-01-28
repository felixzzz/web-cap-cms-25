@extends('backend.layouts.app')

@section('title', $banner_group->position == 'home' ? __('Edit Homepage Banner') : ($banner_group->position == 'pages' ?
    __('Edit Pages Banner') : __('Edit Banner')))

@section('content')
    <form class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" method="post"
        action="{{ route('admin.banner.update', $banner_group) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <input type="hidden" name="position" value="{{ $banner_group->position }}">
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <div class="d-flex flex-column gap-7 gap-lg-10">
                <div class="row">
                    <div class="col-md-12 pb-4">
                        <div class="card card-flush p-3">
                            <div class="card-body p-3">
                                <div id="app">
                                    <div class="d-flex justify-content-between align-items-center mb-5">
                                        <h3 class="my-4">
                                            {{ $banner_group->position == 'home' ? __('Edit Homepage Banner Group') : ($banner_group->position == 'pages' ? __('Edit Pages Banner Group') : __('Edit Banner Group')) }}
                                        </h3>
                                        <a href="{{ $banner_group->position == 'home' ? route('admin.banner.home.index') : ($banner_group->position == 'pages' ? route('admin.banner.pages.index') : route('admin.banner.index')) }}"
                                            class="btn btn-secondary me-4">@lang('Back to list')</a>
                                    </div>
                                    <x-forms.text-input name="title" label="Title" required="1"
                                        placeholder="Banner Group Title" :value="$banner_group->title" />
                                    <accordion-repeater-component url="{{ config('filesystems.disks.s3.url') }}"
                                        :field="{{ json_encode($field) }}" value="{{ json_encode($banners) }}"
                                        component="banners">
                                    </accordion-repeater-component>

                                    <div class="d-flex justify-content-end mt-4">
                                        <a href="{{ $banner_group->position == 'home' ? route('admin.banner.home.index') : ($banner_group->position == 'pages' ? route('admin.banner.pages.index') : route('admin.banner.index')) }}"
                                            class="btn btn-secondary me-2">@lang('Cancel')</a>
                                        <button type="submit" class="btn btn-primary">@lang('Update Banner Group')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
