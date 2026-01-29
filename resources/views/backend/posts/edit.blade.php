@inject('model', '\App\Domains\Post\Models\Post')

@extends('backend.layouts.app')

@section('title', 'Edit Post')

@section('content')
    @php $required = '1'; $hidden = '0'; @endphp
    @if(env('APP_FEATURE') === 'ATOMS')
        @php $required = '0'; $hidden = '1'; @endphp
    @endif
    @if (session('flash_success'))
        <div class="alert alert-success">
            {{ session('flash_success') }}
        </div>
    @endif
    <form class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" method="post" action="{{ route('admin.post.update', ['post' => $post]) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <div class="d-flex flex-column gap-7 gap-lg-10">
                <div class="row">
                    <div class="col-md-12 pb-4">
                        <div class="card card-flush p-3">
                            <div class="card-body p-3">
                                @if($type['type'] != 'managements')
                                <x-forms.text-input name="title" label="Title" required="1" placeholder="The title of post" text="" value="{!! $post->title !!}"/>
                                    <x-forms.text-input name="slug" label="URL/Slug" required="1" placeholder="The URL/Slug of post" text="" value="{!! $post->slug !!}"/>
                                    <x-forms.text-input name="title_en" label="Title EN" required="1" placeholder="The title of post" text="" value="{!! $post->title_en !!}"/>
                                        <x-forms.text-input name="slug_en" label="URL/Slug EN" required="1" placeholder="The URL/Slug  of post" text="" value="{!! $post->slug_en !!}"/>
                                    @if($type['type'] != 'blog')
                                    <div class="d-flex gap-4">
                                        @if ($type['is_category'])
                                        <div class="flex-fill">
                                            <x-forms.select label="Category" name="categories[]" placeholder="Select an option" required="1" multiple="1" hidden="0" text="Choose category that has been defined">--}}
                                                @foreach($categories as $item)
                                                    @if (in_array($item->id, $post->category()->pluck('id')->toArray()))
                                                        <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                                    @else
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endif
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
                                            <select name="post_type" class="form-control form-select-solid mb-2" id="page">
                                                <option value=""></option>
                                                @foreach ($pages as $page)
                                                    <option value="{{ $page['slug'] }}" {{ $post->post_type === $page['slug'] ? 'selected' : '' }}>
                                                        {{ $page['title'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="d-flex gap-4">
                                        <div class="flex-fill">
                                            <div class="mb-8 fv-row fv-plugins-icon-container">
                                                <label for="status" class="form-label">@lang('Post Status')</label>
                                                <select name="status" class="form-control form-control-solid mb-2" required="">
                                                    <option value="{{ $model::STATUS_PUBLISH }}" {{ $post->status === $model::STATUS_PUBLISH ? 'selected' : '' }}>@lang('Publish')</option>
                                                    <option value="{{ $model::STATUS_DRAFT }}" {{ $post->status === $model::STATUS_DRAFT ? 'selected' : '' }}>@lang('Draft')</option>
                                                    <option value="{{ $model::STATUS_SCHEDULE }}" {{ $post->status === $model::STATUS_SCHEDULE ? 'selected' : '' }}>@lang('Schedule')</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    @if($type['type'] == 'blog' ||$type['type'] == 'news' || $type['type'] == 'articles-sustainability') 
                                    <div class="mb-8 fv-row fv-plugins-icon-container">
                                        <label class="form-label">Publish At</label>
                                        <input type="datetime-local" name="published_at" class="form-control mb-2" placeholder="{{ __('Publish time') }}"  value="{{ $post->published_at }}"/>
                                    </div> 
                                    @else
                                    <div class="mb-8 fv-row fv-plugins-icon-container" id="divPublishAt">
                                        <label class="form-label">Schedule At</label>
                                        <input type="datetime-local" name="published_at" id="published_at" class="form-control mb-2" placeholder="{{ __('Publish time') }}"  value="{{ $post->published_at }}"/>
                                    </div>
                                    @endif
                                    @if($type['featured_image'] & $type['type']!= 'products')
                                        <x-forms.filepond-input name="featured_image" label="Featured Image" class="" required="0" src="{{ $post->featured_image() }}"  text="Set the post thumbnail image. Only *.png, *.jpg and *.jpeg image files are accepted" hidden="{{ $hidden }}"/>
                                        <x-forms.text-input name="alt_image" label="Alt Image (ID)" required="{{ $required }}" placeholder="" text="" value="{{ $post->alt_image }}"/>
                                        <x-forms.text-input name="alt_image_en" label="Alt Image (EN)" required="{{ $required }}" placeholder="" text="" value="{{ $post->alt_image_en }}"/>
                                    @endif
                                @endif
                        @if($components)
                        <div class="card card-flush p-3 mt-8">
                            <div class="card-body p-3">
                                <div id="app">
                                    @if($type['type'] == 'managements')
                                    <x-forms.text-input name="title" label="Name" required="{{ $required }}" placeholder="" text="" value="{{ $post->title }}"/>
                                        <div class="flex-fill">
                                            <x-forms.select label="Category" name="categories[]" placeholder="Select an option" required="1" multiple="0" hidden="0" text="Choose category that has been defined">--}}
                                                @foreach($categories as $item)
                                                    @if (in_array($item->id, $post->category()->pluck('id')->toArray()))
                                                        <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                                    @else
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endif
                                                @endforeach
                                            </x-forms.select>
                                        </div>
                                        <div class="flex-fill">
                                            <div class="mb-8 fv-row fv-plugins-icon-container">
                                                <label for="status" class="form-label">@lang('Post Status')</label>
                                                <select name="status" class="form-control form-control-solid mb-2" required="" id="status">
                                                    <option value="{{ $model::STATUS_PUBLISH }}" {{ $post->status === $model::STATUS_PUBLISH ? 'selected' : '' }}>@lang('Publish')</option>
                                                    <option value="{{ $model::STATUS_DRAFT }}" {{ $post->status === $model::STATUS_DRAFT ? 'selected' : '' }}>@lang('Draft')</option>
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
                                                    @endforeach

                                                    @if($type['type'] == 'news' || $type['type'] == 'blog')
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
                                                                    @php
                                                                        $activeBanner = $post->activeBanners->where('location', $location)->where('language', $lang_code)->first();
                                                                    @endphp
                                                                    <div class="tab-pane fade {{$loop->first && $location == 'left' ? 'show active' : ''}}" 
                                                                         id="v-pills-{{$location}}-{{$lang_code}}" 
                                                                         role="tabpanel" 
                                                                         aria-labelledby="v-pills-{{$location}}-{{$lang_code}}-tab">
                                                                        <div class="mb-5">
                                                                            <label class="form-label">Banner Group</label>
                                                                            <select name="banner_active[{{$lang_code}}][{{$location}}][group_id]" 
                                                                                    class="form-select form-select-solid banner-group-select" 
                                                                                    data-control="select2" 
                                                                                    data-placeholder="Select Banner Group">
                                                                                <option value="">Select Banner Group</option>
                                                                                @foreach($bannerGroups as $group)
                                                                                    <option value="{{ $group->id }}" {{ $activeBanner && $activeBanner->banner_group_id == $group->id ? 'selected' : '' }}>
                                                                                        {{ $group->title }} ({{ $group->items_count }} banners)
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-md-6 mb-5">
                                                                                <label class="form-label">Start Date</label>
                                                                                <input type="datetime-local" 
                                                                                       name="banner_active[{{$lang_code}}][{{$location}}][start_date]" 
                                                                                       class="form-control form-control-solid"
                                                                                       value="{{ $activeBanner && $activeBanner->start_date ? $activeBanner->start_date->format('Y-m-d\TH:i') : '' }}">
                                                                            </div>
                                                                            <div class="col-md-6 mb-5">
                                                                                <label class="form-label">End Date</label>
                                                                                <input type="datetime-local" 
                                                                                       name="banner_active[{{$lang_code}}][{{$location}}][end_date]" 
                                                                                       class="form-control form-control-solid"
                                                                                       value="{{ $activeBanner && $activeBanner->end_date ? $activeBanner->end_date->format('Y-m-d\TH:i') : '' }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="mt-3">
                                                                            <button type="button" style="font-size: 10px!important; padding: 0px 5px!important;" class="btn btn-sm btn-light-danger clear-banner-btn">Clear Banner Config in this Position</button>
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

                        <button type="submit" class="btn btn-primary" id="btnSubmit">Update Post</button>

                    </div>
                </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @include('backend.posts.partials.banner-embed-modal')
@endsection

@push('scripts')

    <script>
        $('#divPublishAt').hide();
        $('#post_status').change(function(){
            if($('#post_status').val() == 'schedule') {
                $('#divPublishAt').show();
                $('#published_at').prop('required',true);
                $('#btnSubmit').html('Update & Schedule')
            } else {
                $('#divPublishAt').hide();
                $('#published_at').prop('required',false);

                if($('#post_status').val() == 'publish') {
                    $('#btnSubmit').html('Update Post')
                } else {
                    $('#btnSubmit').html('Update Draft')
                }

            }
        });

        @if($post->status === $model::STATUS_SCHEDULE)
        $('#divPublishAt').show();
        @endif
    </script>
@endpush
@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2 for all visible selects initially
        $('.banner-group-select').select2({
            width: '100%',
            minimumResultsForSearch: 0 // Always show search
        });

        // specific fix for tabs: destroy and re-init on show
        $('button[data-bs-toggle="pill"]').on('shown.bs.tab', function (e) {
            var targetId = $(e.target).data('bs-target'); // e.g. #v-pills-left-en
            var $target = $(targetId);
            
            $target.find('.banner-group-select').each(function() {
                 if ($(this).data('select2')) {
                    $(this).select2('destroy');
                 }
                 $(this).select2({
                    width: '100%',
                    minimumResultsForSearch: 0 // Always show search
                 });
            });
        });
    });

    $(document).on('click', '.clear-banner-btn', function() {
        var container = $(this).closest('.tab-pane');
        container.find('select').val(null).trigger('change'); // .val(null) works better for select2 clearing
        container.find('input[type="datetime-local"]').val('');
    });
</script>
@endpush
