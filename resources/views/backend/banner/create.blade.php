@extends('backend.layouts.app')

@section('title', $position == 'home' ? __('Create Homepage Banner') : ($position == 'pages' ? __('Create Pages Banner')
    : __('Create Banner')))
@section('content')
    <form class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" method="post"
        action="{{ route('admin.banner.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="position" value="{{ $position }}">
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <div class="d-flex flex-column gap-7 gap-lg-10">
                <div class="row">
                    <div class="col-md-12 pb-4">
                        <div class="card card-flush p-3">
                            <div class="card-body p-3">
                                <div id="app">
                                    <div class="d-flex justify-content-between align-items-center mb-5">
                                        <h3 class="my-4">
                                            {{ $position == 'home' ? __('Create Homepage BannerGroup') : ($position == 'pages' ? __('Create Pages BannerGroup') : __('Create Banner Group')) }}
                                        </h3>
                                        <a href="{{ $position == 'home' ? route('admin.banner.home.index') : ($position == 'pages' ? route('admin.banner.pages.index') : route('admin.banner.index')) }}"
                                            class="btn btn-secondary me-4">@lang('Back to list')</a>
                                    </div>
                                    <x-forms.text-input name="title" label="Title" required="1"
                                        placeholder="Banner Group Title" />
                                    <accordion-repeater-component url="{{ config('filesystems.disks.s3.url') }}"
                                        :field="{{ json_encode($field) }}" value="[]" component="banners">
                                    </accordion-repeater-component>

                                    <div class="d-flex justify-content-end mt-4">
                                        <a href="{{ $position == 'home' ? route('admin.banner.home.index') : ($position == 'pages' ? route('admin.banner.pages.index') : route('admin.banner.index')) }}"
                                            class="btn btn-secondary me-2">@lang('Cancel')</a>
                                        <button type="submit" class="btn btn-primary">@lang('Create Banner Group')</button>
                                    </div>
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
