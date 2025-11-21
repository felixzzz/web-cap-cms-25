@extends('backend.layouts.app')

@section('title', __('Edit Document'))

@section('content')
    <x-forms.post :action="route('admin.document.update', ['document' => $document->id, 'template' => $template])" enctype="multipart/form-data">
        @method('PATCH')
        <x-backend.card>
            <x-slot name="header">
                @lang('Edit Document')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.document.index', ['template' => $document->page])"
                              :text="__('Cancel')"/>
            </x-slot>
            <x-slot name="body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="flex-fill">
                            <div class="mb-8 fv-row fv-plugins-icon-container">
                                <label for="page" class="form-label">@lang('Page')</label>
                                <select name="page" class="form-control form-select-solid mb-2" required id="page">
                                    @foreach ($pages as $page)
                                        @if($template)
                                            @if($page['name'] == $template)
                                                <option value="{{ $page['name'] }}" {{ $document->page === $page['name'] ? 'selected' : '' }}>{{ $page['label'] }}</option>
                                            @endif
                                        @else
                                            <option value="{{ $page['name'] }}" {{ $document->page === $page['name'] ? 'selected' : '' }}>{{ $page['label'] }}</option>
                                        @endif
                                    @endforeach
                                    @if($template =='news')
                                        <option value="news" {{ $document->page === 'news' ? 'selected' : '' }}>Press Releases</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="flex-fill">
                            <div class="mb-8 fv-row fv-plugins-icon-container">
                                <label for="section" class="form-label">@lang('Section')</label>
                                <select name="section" class="form-control form-select-solid mb-2" id="section-select">
                                    <option value="">Select a section</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section['keyName'] }}" {{ $document->section === $section['keyName'] ? 'selected' : '' }}>
                                            {{ $section['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        @if($template !== 'about_who_we_are')
                            <div class="flex-fill">
                                <div class="mb-8 fv-row fv-plugins-icon-container">
                                    <label for="category" class="form-label">@lang('Category')</label>
                                    <select name="category_id" class="form-control form-select-solid mb-2" id="category">
                                        <option value="">Select a category</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ $document->category_id == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name_id }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <x-forms.text-input name="document_name_id" label="Name ID" required="1" placeholder="The name of documents" value="{{ old('document_name', $document->document_name_id) }}"/>
                        <x-forms.text-input name="document_name_en" label="Name EN" required="1" placeholder="The name of documents" value="{{ old('document_name', $document->document_name_en) }}"/>
                        <x-forms.text-input name="format" label="Document Format" placeholder="The format of documents, eg:pdf,excel,docs,rar" value="{{ old('format', $document->format) }}"/>
                            <div class="mb-8 fv-row fv-plugins-icon-container">
                                <label for="description_id" class="form-label">Description ID</label>
                                <textarea name="description_id" class="form-control" placeholder="Description in ID">{{ old('description_id', $document->description_id) }}</textarea>
                            </div>
                    
                            <!-- Description EN -->
                            <div class="mb-8 fv-row fv-plugins-icon-container">
                                <label for="description_en" class="form-label">Description EN</label>
                                <textarea name="description_en" class="form-control" placeholder="Description in EN">{{ old('description_en', $document->description_en) }}</textarea>
                            </div>
                    
                            <!-- Language -->
                            <div class="mb-8 fv-row fv-plugins-icon-container">
                                <label for="language" class="form-label">Language</label>
                                <input type="text" name="language" class="form-control" value="{{ old('language', $document->language) }}" placeholder="Language">
                            </div>
                    
                            <!-- Author -->
                            <div class="mb-8 fv-row fv-plugins-icon-container">
                                <label for="author" class="form-label">Author</label>
                                <input type="text" name="author" class="form-control" value="{{ old('author', $document->author) }}" placeholder="Author">
                            </div>
                    
                            <!-- Publisher -->
                            <div class="mb-8 fv-row fv-plugins-icon-container">
                                <label for="publisher" class="form-label">Publisher</label>
                                <input type="text" name="publisher" class="form-control" value="{{ old('publisher', $document->publisher) }}" placeholder="Publisher">
                            </div>
                    
                            <!-- Release Year -->
                            <div class="mb-8 fv-row fv-plugins-icon-container">
                                <label for="release_year" class="form-label">Release Year</label>
                                <input type="number" name="release_year" class="form-control" value="{{ old('release_year', $document->release_year) }}" placeholder="Release Year">
                            </div>
                    
                            <!-- Pages -->
                            <div class="mb-8 fv-row fv-plugins-icon-container">
                                <label for="pages" class="form-label">Pages</label>
                                <input type="number" name="pages" class="form-control" value="{{ old('pages', $document->pages) }}" placeholder="Number of Pages">
                            </div>
                        <div class="mb-8 fv-row fv-plugins-icon-container">
                            <label class="form-label">Publish At</label>
                            <input type="date" name="published_at" id="published_at" class="form-control mb-2" placeholder="{{ __('Publish time') }}" value="{{$published_date}}" required />
                        </div>
                        <div class="mb-8 fv-row fv-plugins-icon-container">
                            <label for="document_file_id" class="">Document File ID</label>
                            <input 
                                type="file" 
                                id="document_file_id" 
                                name="document_file_id" 
                                class="form-control" 
                            />
                            @if($document->document_file_id)
                                <p>Current File: <a href="{{ Storage::url($document->document_file_id) }}" target="_blank">View File</a></p>
                                <input type="checkbox" name="remove_document_file_id" value="1"> Remove current file
                            @endif
                        </div>
                        <div class="mb-8 fv-row fv-plugins-icon-container">
                            <label for="document_file_en" class="">Document File EN</label>
                            <input 
                                type="file" 
                                id="document_file_en" 
                                name="document_file_en" 
                                class="form-control" 
                            />
                            @if($document->document_file_en)
                                <p>Current File: <a href="{{ Storage::url($document->document_file_en) }}" target="_blank">View File</a></p>
                                <input type="checkbox" name="remove_document_file_en" value="1"> Remove current file
                            @endif
                        </div>
                        <div class="mb-8 fv-row fv-plugins-icon-container">

                            @if($document->image)
                                <image src="{{ Storage::url($document->image) }}"><br><br>
                                <input type="checkbox" name="remove_image" value="1"> Remove<br>
                            @endif
                            <br>
                            <label for="image" class="">Image</label>
                            <input 
                                type="file" 
                                id="image" 
                                name="image" 
                                class="form-control" 
                                accept=".png, .jpg, .webp"
                            />
                            <div class="mb-8 fv-row fv-plugins-icon-container">
                                <label for="alt_image" class="form-label">Alt Image (ID)</label>
                                <input type="text" name="alt_image" class="form-control" placeholder="Alt Image" value="{{ old('pages', $document->alt_image) }}">
                            </div>
                            <div class="mb-8 fv-row fv-plugins-icon-container">
                                <label for="alt_image_en" class="form-label">Alt Image (EN)</label>
                                <input type="text" name="alt_image_en" class="form-control" placeholder="Alt Image" value="{{ old('pages', $document->alt_image_en) }}">
                            </div>
                        </div>
                    </div><!--col-md-8-->
                </div>
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-end" type="submit">@lang('Update')</button>
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
            const pages = @json($pages);
            const selectedPage = $(this).val();
            const pageData = pages.find(page => page.name === selectedPage);

            $('#section-select').empty();
            $('#section-select').append('<option value="">Select a section</option>');

            if (pageData) {
                const sections = pageData.components || [];
                sections.forEach(component => {
                    $('#section-select').append(
                        `<option value="${component.keyName}" ${component.keyName === '{{ $document->section }}' ? 'selected' : ''}>${component.label}</option>`
                    );
                });
            }
        });

        function validateFileSize(fileInput) {
            const file = fileInput.files[0];
            if (file && file.size > 35 * 1024 * 1024) { // 15 MB in bytes
                alert('File size exceeds 35 MB.');
                fileInput.value = ''; // Clear the input field
            }
        }
        function validateImageSize(fileInput) {
            const file = fileInput.files[0];
            if (file && file.size > 400 * 1024) { // 400kb
                alert('File size exceeds 400kb.');
                fileInput.value = ''; // Clear the input
            }
        }

        $('#document_file_id').change(function() {
            validateFileSize(this);
        });

        $('#document_file_en').change(function() {
            validateFileSize(this);
        });
        $('#image').change(function() {
            validateImageSize(this);
        });
        $('#page').trigger('change');
    });
</script>
@endpush
