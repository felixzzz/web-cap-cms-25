@extends('backend.layouts.app')

@section('title', __('Create Banner'))

@section('content')
    <form class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" method="post"
        action="{{ route('admin.banner.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <div class="d-flex flex-column gap-7 gap-lg-10">
                <div class="row">
                    <div class="col-md-12 pb-4">
                        <div class="card card-flush p-3">
                            <div class="card-body p-3">
                                <div id="app">
                                    <x-forms.text-input name="title" label="Title" required="1"
                                        placeholder="Banner Group Title" />
                                    <accordion-repeater-component url="{{ config('app.url') . '/storage' }}"
                                        :field="{{ json_encode($field) }}" value="[]" component="banners">
                                    </accordion-repeater-component>

                                    <div class="d-flex justify-content-end mt-4">
                                        <a href="{{ route('admin.banner.index') }}"
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
