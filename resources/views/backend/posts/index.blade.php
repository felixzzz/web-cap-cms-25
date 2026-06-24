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

    @if(isset($page))
        <x-forms.post :action="route('admin.post.update-seo', $page)">
            @method('PATCH')
            <x-backend.card class="mt-8">
                <x-slot name="header">
                    @lang('SEO Settings for ' . ucwords($type['name']) . ' Page')
                </x-slot>

                <x-slot name="body">
                    <ul class="nav nav-tabs" id="seoTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="id-seo-tab" data-bs-toggle="tab" data-bs-target="#id-seo" type="button" role="tab" aria-controls="id-seo" aria-selected="true">
                                INDONESIAN
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="en-seo-tab" data-bs-toggle="tab" data-bs-target="#en-seo" type="button" role="tab" aria-controls="en-seo" aria-selected="false">
                                ENGLISH
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content pt-4" id="seoTabContent">
                        <div class="tab-pane fade show active" id="id-seo" role="tabpanel" aria-labelledby="id-seo-tab">
                            <div class="mb-8 fv-row fv-plugins-icon-container">
                                <label class="form-label">@lang('Meta Title (ID)')</label>
                                <input type="text" name="seo_meta[meta_title_id]" class="form-control" value="{{ old('seo_meta.meta_title_id', $meta['seo_meta']->meta_title_id ?? '') }}" placeholder="Meta Title (ID)">
                            </div>
                            <div class="mb-8 fv-row fv-plugins-icon-container">
                                <label class="form-label">@lang('Meta Description (ID)')</label>
                                <textarea name="seo_meta[meta_desc_id]" class="form-control" rows="3" placeholder="Meta Description (ID)">{{ old('seo_meta.meta_desc_id', $meta['seo_meta']->meta_desc_id ?? '') }}</textarea>
                            </div>
                            <div class="mb-8 fv-row fv-plugins-icon-container">
                                <label class="form-label">@lang('SEO Schema (JSON-LD) (ID)')</label>
                                <div id="editor_seo_schema_id" class="border rounded" style="height: 300px; font-size: 14px; font-family: monospace;"></div>
                                <textarea id="hidden_seo_schema_id" name="seo[seo_schema_id]" class="d-none">{{ old('seo.seo_schema_id', $meta['seo']->seo_schema_id ?? '') }}</textarea>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <button type="button" class="btn btn-sm btn-light-primary py-1 px-3" id="format_seo_schema_id">🪄 Format JSON</button>
                                    <div id="error_seo_schema_id" class="text-danger fs-7" style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="en-seo" role="tabpanel" aria-labelledby="en-seo-tab">
                            <div class="mb-8 fv-row fv-plugins-icon-container">
                                <label class="form-label">@lang('Meta Title (EN)')</label>
                                <input type="text" name="seo_meta[meta_title_en]" class="form-control" value="{{ old('seo_meta.meta_title_en', $meta['seo_meta']->meta_title_en ?? '') }}" placeholder="Meta Title (EN)">
                            </div>
                            <div class="mb-8 fv-row fv-plugins-icon-container">
                                <label class="form-label">@lang('Meta Description (EN)')</label>
                                <textarea name="seo_meta[meta_desc_en]" class="form-control" rows="3" placeholder="Meta Description (EN)">{{ old('seo_meta.meta_desc_en', $meta['seo_meta']->meta_desc_en ?? '') }}</textarea>
                            </div>
                            <div class="mb-8 fv-row fv-plugins-icon-container">
                                <label class="form-label">@lang('SEO Schema (JSON-LD) (EN)')</label>
                                <div id="editor_seo_schema_en" class="border rounded" style="height: 300px; font-size: 14px; font-family: monospace;"></div>
                                <textarea id="hidden_seo_schema_en" name="seo[seo_schema_en]" class="d-none">{{ old('seo.seo_schema_en', $meta['seo']->seo_schema_en ?? '') }}</textarea>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <button type="button" class="btn btn-sm btn-light-primary py-1 px-3" id="format_seo_schema_en">🪄 Format JSON</button>
                                    <div id="error_seo_schema_en" class="text-danger fs-7" style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">@lang('Update SEO Settings')</button>
                </x-slot>
            </x-backend.card>
        </x-forms.post>
    @endif

    @once
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>
    @endonce

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function setupAce(containerId, hiddenInputId, errorDivId, formatBtnId) {
                var container = document.getElementById(containerId);
                var hiddenInput = document.getElementById(hiddenInputId);
                var errorDiv = document.getElementById(errorDivId);
                var formatBtn = document.getElementById(formatBtnId);

                if (!container || !hiddenInput) return;

                var editor = ace.edit(container);
                editor.setTheme("ace/theme/chrome");
                editor.session.setMode("ace/mode/json");
                editor.session.setUseWorker(false); // Disable web workers to prevent cross-origin issues
                editor.setShowPrintMargin(false);
                editor.setOptions({
                    tabSize: 2,
                    useSoftTabs: true
                });

                // Set initial value
                editor.setValue(hiddenInput.value || "{\n  \"@context\": \"https://schema.org\",\n  \"@type\": \"\"\n}");
                editor.clearSelection();

                // Sync editor updates back to target input
                editor.session.on('change', function() {
                    var value = editor.getValue();
                    hiddenInput.value = value;
                    try {
                        if (value.trim()) {
                            JSON.parse(value);
                        }
                        errorDiv.style.display = "none";
                        container.style.borderColor = "#e4e6ef";
                    } catch (e) {
                        errorDiv.innerText = "⚠️ Invalid JSON: " + e.message;
                        errorDiv.style.display = "block";
                        container.style.borderColor = "#f1416c";
                    }
                });

                // Format/Beautify button functionality
                formatBtn.addEventListener("click", function() {
                    try {
                        var content = editor.getValue();
                        if (content.trim()) {
                            var parsed = JSON.parse(content);
                            editor.setValue(JSON.stringify(parsed, null, 2));
                            editor.clearSelection();
                        }
                    } catch (e) {
                        alert("Cannot format: JSON is invalid.\n" + e.message);
                    }
                });
            }

            setupAce("editor_seo_schema_id", "hidden_seo_schema_id", "error_seo_schema_id", "format_seo_schema_id");
            setupAce("editor_seo_schema_en", "hidden_seo_schema_en", "error_seo_schema_en", "format_seo_schema_en");
        });
    </script>
@endsection
