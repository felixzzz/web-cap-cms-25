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
                                <textarea name="seo[seo_schema_id]" class="form-control" rows="8" placeholder="SEO Schema (JSON-LD) (ID)">{{ old('seo.seo_schema_id', $meta['seo']->seo_schema_id ?? '') }}</textarea>
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
                                <textarea name="seo[seo_schema_en]" class="form-control" rows="8" placeholder="SEO Schema (JSON-LD) (EN)">{{ old('seo.seo_schema_en', $meta['seo']->seo_schema_en ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">@lang('Update SEO Settings')</button>
                </x-slot>
            </x-backend.card>
        </x-forms.post>
    @endif
@endsection
