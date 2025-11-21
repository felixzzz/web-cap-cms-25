@extends('backend.layouts.app')

@section('title', __('Create Category'))

@section('content')
    <x-forms.post :action="route('admin.document-categories.store', ['template' => $template])" enctype="multipart/form-data">
        <x-backend.card>
            <x-slot name="header">
                @lang('Create Category')
            </x-slot>
            @if (session('flash_success'))
                <div class="alert alert-success">
                    {{ session('flash_success') }}
                </div>
            @endif

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.document-categories.index')"
                              :text="__('Cancel')"/>
            </x-slot>

            <x-slot name="body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="flex-fill">
                            <div class="mb-8 fv-row fv-plugins-icon-container">
                                <label for="page" class="form-label">@lang('Page')</label>
                                <select name="page" class=" form-control form-select-solid mb-2" required="1" id="page">
                                    @foreach ($pages as $page)
                                        @if(isset($page['is_document_category']))
                                            <option value="{{ $page['name'] }}">{{ $page['label'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex-fill">
                            <div class="mb-8 fv-row fv-plugins-icon-container">
                                <label for="section" class="form-label">@lang('Section')</label>
                                <select name="section" class=" form-control form-select-solid mb-2" id="section-select">
                                    <option value="">Select a section</option>
                                </select>
                            </div>
                        </div>
                        <x-forms.text-input name="name_id" label="Name ID" required="1" placeholder="The name of category"/>
                        <x-forms.text-input name="name_en" label="Name EN" required="1" placeholder="The name of category (english version)"/>

                    </div><!--col-md-8-->

                </div>
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-end" type="submit">@lang('Create')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>

@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Handle change event for page select dropdown
        $('#page').change(function() {
            // Retrieve the pages data from the server-side
            const pages = @json($pages);

            // Get the selected page value
            const selectedPage = $(this).val();

            // Find the data associated with the selected page
            const pageData = pages.find(page => page.name === selectedPage);
            console.log('Page data:', pageData.components);

            // Clear previous options in the section dropdown
            $('#section-select').empty();
            $('#section-select').append('<option value="">Select a section</option>');

            if (pageData) {
                // Add new section options based on the selected page
                const sections = pageData.components || [];
                sections.forEach(component => {
                    if (component.is_document_category) {
                        $('#section-select').append(
                            `<option value="${component.keyName}">${component.label}</option>`
                        );
                    }
                });
            }
        });
    });
</script>
@endpush
