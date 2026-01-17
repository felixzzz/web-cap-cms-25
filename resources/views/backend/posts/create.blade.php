@inject('model', '\App\Domains\Post\Models\Post')

@extends('backend.layouts.app')

@section('title', 'Create Post')

@section('content')
@php $required = '1'; $hidden = '0'; @endphp
@if(env('APP_FEATURE') === 'ATOMS')
    @php $required = '0'; $hidden = '1'; @endphp
@endif
    <form class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" method="post" action="{{ route('admin.post.store', ['type' => $type['type']]) }}" enctype="multipart/form-data">
        @csrf
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <div class="d-flex flex-column gap-7 gap-lg-10">
                <div class="row">
                    <div class="col-md-12 pb-4">
                        <div class="card card-flush p-3">
                            <div class="card-body p-3">
                                @if($type['type'] != 'managements')
                                <x-forms.text-input name="title" label="Title" required="{{ $required }}" placeholder="The title of post" text=""/>
                                <x-forms.text-input name="slug" label="URL/Slug ID" required="{{ $required }}" placeholder="The url of post" text=""/>
                                <x-forms.text-input name="title_en" label="Title EN" required="{{ $required }}" placeholder="The title of post" text=""/>
                                <x-forms.text-input name="slug_en" label="URL/Slug EN" required="{{ $required }}" placeholder="The url of post" text=""/>
                                @if($type['type'] != 'blog')
                                <div class="d-flex gap-4">
                                    @if ($type['is_category'])
                                        <div class="flex-fill">
                                            <x-forms.select label="Category" name="categories[]" placeholder="Select an option" required="{{ $required }}" multiple="1" text="Choose category that has been defined" hidden="{{ $hidden }}">
                                                @foreach($categories as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </x-forms.select>
                                        </div>
                                    @endif
                                </div>
                                @endif
                                @if($type['type'] == 'news')                        
                                <div class="flex-fill">
                                    <div class="mb-8 fv-row fv-plugins-icon-container">
                                        <label for="page" class="form-label">@lang('Business Line')</label>
                                        <select name="post_type" class=" form-control form-select-solid mb-2" id="page">
                                            <option value=""></option>
                                            @foreach ($pages as $page)
                                                <option value="{{ $page['slug'] }}">{{ $page['title'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
                                <div class="d-flex gap-4">
                                    <div class="flex-fill">
                                        <div class="mb-8 fv-row fv-plugins-icon-container">
                                            <label for="status" class="form-label">@lang('Post Status')</label>
                                            <select name="status" class=" form-control form-select-solid mb-2" required="">
                                                <option value="{{ $model::STATUS_PUBLISH }}">@lang('Publish')</option>
                                                <option value="{{ $model::STATUS_SCHEDULE }}">@lang('Schedule')</option>
                                                <option value="{{ $model::STATUS_DRAFT }}">@lang('Draft')</option>
                                            </select>
                                        </div>
                                        @if($type['type'] == 'blog' ||$type['type'] == 'news' || $type['type'] == 'articles-sustainability')  

                                        <div class="mb-8 fv-row fv-plugins-icon-container" >
                                            <label class="form-label">Publish At</label>
                                            <input type="datetime-local" name="published_at" class="form-control mb-2" placeholder="{{ __('Publish time') }}" />
                                        </div>
                                        @else
                                        <div class="mb-8 fv-row fv-plugins-icon-container" id="divPublishAt">
                                            <label class="form-label">Schedule At</label>
                                            <input type="datetime-local" name="published_at" id="published_at" class="form-control mb-2" placeholder="{{ __('Publish time') }}" />
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @if($type['featured_image'] & $type['type']!= 'products')
                                <x-forms.filepond-input name="featured_image" label="Featured Image" class="" required="0" src="" text="Set the post thumbnail image. Only *.png, *.jpg, *.webp and *.jpeg image files are accepted" hidden="{{ $hidden }}"/>
                                <x-forms.text-input name="alt_image" label="Alt Image (ID)" required="{{ $required }}" placeholder="" text="" />
                                <x-forms.text-input name="alt_image_en" label="Alt Image (EN)" required="{{ $required }}" placeholder="" text="" />
                                    
                                @endif
                            @endif
                        @if($components)
                            <div class="card card-flush p-3 mt-8">
                                <div class="card-body p-3">
                                    <div id="app">
                                        @if($type['type'] == 'managements')
                                        <x-forms.text-input name="title" label="Name" required="{{ $required }}" placeholder="" text=""/>
                                        <div class="d-flex gap-4">
                                            @if ($type['is_category'])
                                                <div class="flex-fill">
                                                    <x-forms.select label="Category" name="categories[]" placeholder="Select an option" required="{{ $required }}" multiple="0" text="Choose category that has been defined" hidden="{{ $hidden }}">
                                                        @foreach($categories as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </x-forms.select>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-fill">
                                            <div class="mb-8 fv-row fv-plugins-icon-container">
                                                <label for="status" class="form-label">@lang('Status')</label>
                                                <select name="status" class=" form-control form-select-solid mb-2" required="" id="status">
                                                    <option value="{{ $model::STATUS_PUBLISH }}">@lang('Publish')</option>
                                                    <option value="{{ $model::STATUS_DRAFT }}">@lang('Draft')</option>
                                                </select>
                                            </div>
                                        </div>
                                        @endif
                                        @if (!isset($template['multilanguage']))
                                        @php $template['multilanguage'] = 'false' @endphp
                                        @endif
                                        @if ($template['multilanguage'] === 'true')
                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                @foreach ($template['lang_option'] as $lang)
                                                    @php
                                                        $lang_title = $lang['name'];
                                                        $lang_code = $lang['value'];
                                                    @endphp
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link @if ($loop->first) active @endif"
                                                            id="{{ $lang_code }}-tab" data-bs-toggle="tab"
                                                            data-bs-target="#{{ $lang_code }}" type="button" role="tab"
                                                            aria-controls="{{ $lang_code }}" aria-selected="true">
                                                            {{ strtoupper($lang_title) }}
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div class="tab-content" id="myTabContent">
                                                @foreach ($template['lang_option'] as $lang)
                                                    @php
                                                        $lang_title = $lang['name'];
                                                        $lang_code = $lang['value'];
                                                    @endphp
                                                    <div class="tab-pane fade @if ($loop->first) show active @endif"
                                                        id="{{ $lang_code }}" role="tabpanel"
                                                        aria-labelledby="{{ $lang_code }}-tab">
                                                        @foreach ($components as $component)
                                                            @include('components.backend.component-wrapper-v2', [
                                                                'component' => $component,
                                                                'loopIndex' => $loop->index,
                                                                'postId' => $post->id,
                                                                'lang' => $lang_code,
                                                                'lang_option' => $template['lang_option'],
                                                            ])
                                                            ])
                                                        @endforeach

                                                        @if($type['type'] == 'news')
                                                            <div class="separator my-10"></div>
                                                            <h3 class="text-dark fw-bolder mb-5">Banner Configuration</h3>
                                                            
                                                            <div class="d-flex align-items-start">
                                                                <div class="nav flex-column nav-pills me-3" id="v-pills-tab-{{$lang_code}}" role="tablist" aria-orientation="vertical">
                                                                    @foreach(['left', 'right', 'center', 'bottom'] as $location)
                                                                        <button class="nav-link {{$loop->first && $location == 'left' ? 'active' : ''}}" 
                                                                                id="v-pills-{{$location}}-{{$lang_code}}-tab" 
                                                                                data-bs-toggle="pill" 
                                                                                data-bs-target="#v-pills-{{$location}}-{{$lang_code}}" 
                                                                                type="button" role="tab" 
                                                                                aria-controls="v-pills-{{$location}}-{{$lang_code}}" 
                                                                                aria-selected="{{$loop->first ? 'true' : 'false'}}">
                                                                            {{ ucfirst($location) }}
                                                                        </button>
                                                                    @endforeach
                                                                </div>
                                                                <div class="tab-content flex-grow-1" id="v-pills-tabContent-{{$lang_code}}">
                                                                    @foreach(['left', 'right', 'center', 'bottom'] as $location)
                                                                        <div class="tab-pane fade {{$loop->first && $location == 'left' ? 'show active' : ''}}" 
                                                                             id="v-pills-{{$location}}-{{$lang_code}}" 
                                                                             role="tabpanel" 
                                                                             aria-labelledby="v-pills-{{$location}}-{{$lang_code}}-tab">
                                                                            
                                                                            <div class="mb-5">
                                                                                <label class="form-label">Banner Group</label>
                                                                                <select name="banner_active[{{$lang_code}}][{{$location}}][group_id]" class="form-select form-select-solid">
                                                                                    <option value="">Select Banner Group</option>
                                                                                    @foreach($bannerGroups as $group)
                                                                                        <option value="{{ $group->id }}">
                                                                                            {{ $group->title }} ({{ $group->banners_count }} banners)
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="col-md-6 mb-5">
                                                                                    <label class="form-label">Start Date</label>
                                                                                    <input type="datetime-local" 
                                                                                           name="banner_active[{{$lang_code}}][{{$location}}][start_date]" 
                                                                                           class="form-control form-control-solid">
                                                                                </div>
                                                                                <div class="col-md-6 mb-5">
                                                                                    <label class="form-label">End Date</label>
                                                                                    <input type="datetime-local" 
                                                                                           name="banner_active[{{$lang_code}}][{{$location}}][end_date]" 
                                                                                           class="form-control form-control-solid">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            @foreach ($components as $component)
                                                @include('components.backend.component-wrapper-v2', [
                                                    'component' => $component,
                                                    'loopIndex' => $loop->index,
                                                    'postId' => $post->id,
                                                ])
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary" id="btnSubmit">Save & Publish</button>

                    </div>
                </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')

<script>
    $('#divPublishAt').hide();
    $('#post_status').change(function(){
        console.log("trigger");
        if($('#post_status').val() == 'schedule') {
            $('#divPublishAt').show();
            $('#published_at').prop('required',true);
            $('#btnSubmit').html('Save & Schedule')
        } else {
            $('#divPublishAt').hide();
            $('#published_at').prop('required',false);

            if($('#post_status').val() == 'publish') {
                $('#btnSubmit').html('Save & Publish')
            } else {
                $('#btnSubmit').html('Save Draft')
            }

        }
    });
</script>
@endpush
