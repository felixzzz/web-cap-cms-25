@inject('model', '\App\Domains\Document\Models\DocumentCategory')

@extends('backend.layouts.app')

@section('title', __('Update'))

@section('content')
    <x-forms.patch :action="route('admin.document-categories.update', ['category' => $category, 'template' => $template])" enctype="multipart/form-data">
        <x-backend.card>
            <x-slot name="header">
                @lang('Update')
            </x-slot>
            @if (session('flash_success'))
                <div class="alert alert-success">
                    {{ session('flash_success') }}
                </div>
            @endif

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.document-categories.index')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                <div class="row">
                    <div class="col-md-12">
                        <x-forms.text-input name="name_id" label="Name ID" required="1" placeholder="The name of category" value="{{$category->name_id}}"/>
                        <x-forms.text-input name="name_en" label="Name EN" required="1" placeholder="The name of category" value="{{$category->name_en}}"/>
                            <div class="flex-fill">
                                <div class="mb-8 fv-row fv-plugins-icon-container">
                                    <label for="page" class="form-label">@lang('Page')</label>
                                    <select name="page" class="form-control form-select-solid mb-2" required id="page">
                                        @foreach ($pages as $page)
                                            @if(isset($page['is_document_category']))
                                                <option value="{{ $page['name'] }}" {{ $category->page === $page['name'] ? 'selected' : '' }}>
                                                    {{ $page['label'] }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="flex-fill">
                                <div class="mb-8 fv-row fv-plugins-icon-container">
                                    <label for="section" class="form-label">@lang('Section')</label>
                                    <select name="section" class="form-control form-select-solid mb-2" id="section-select">
                                        <option value="">Select a section</option>
                                        @foreach ($sections as $section)
                                            <option value="{{ $section['keyName'] }}" {{ $category->section === $section['keyName'] ? 'selected' : '' }}>
                                                {{ $section['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-end" type="submit">@lang('Update')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.patch>
    <script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'content' );
    </script>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Handle change event for page select dropdown
        $('#page').change(function() {
            const pages = @json($pages);
            const selectedPage = $(this).val();
            const pageData = pages.find(page => page.name === selectedPage);

            $('#section-select').empty();
            $('#section-select').append('<option value="">Select a section</option>');

            if (pageData) {
                const sections = pageData.components || [];
                sections.forEach(component => {
                    $('#section-select').append(
                        `<option value="${component.keyName}" ${component.keyName === '{{ $category->section }}' ? 'selected' : ''}>${component.label}</option>`
                    );
                });
            }
        });
        $('#page').trigger('change');
    });
</script>
@endpush
