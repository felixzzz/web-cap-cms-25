@extends('backend.layouts.app')

@section('title', 'Detail ' . ucfirst($type->name))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Detail ' . ucfirst($type->name))
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action me-2" :href="route('admin.posttype.index')" :text="__('Back')" />
            <x-utils.edit-button class="card-header-action" :href="route('admin.posttype.edit', $type)" :text="__('Edit')" />
        </x-slot>

        <x-slot name="body">
            <table class="table table-hover">
                <tr>
                    <th>@lang('ID')</th>
                    <td>{{ $type->id }}</td>
                </tr>

                <tr>
                    <th>@lang('Sort Order')</th>
                    <td>{{ $type->sort }}</td>
                </tr>

                <tr>
                    <th>@lang('Name')</th>
                    <td>{{ $type->name }}</td>
                </tr>

                <tr>
                    <th>@lang('Slug')</th>
                    <td>{{ $type->slug }}</td>
                </tr>

                <tr>
                    <th>@lang('Total Post')</th>
                    <td>{{ $type->post()->count() }} Posts</td>
                </tr>

{{--                <tr>--}}
{{--                    <th>@lang('Content')</th>--}}
{{--                    <td>{!! clean($post->content) !!}</td>--}}
{{--                </tr>--}}

{{--                <tr>--}}
{{--                    <th>@lang('Author')</th>--}}
{{--                    <td>{{ $post->user->name }}</td>--}}
{{--                </tr>--}}

{{--                <tr>--}}
{{--                    <th>@lang('Status')</th>--}}
{{--                    <td>{{ ucfirst($post->status) }}</td>--}}
{{--                </tr>--}}

{{--                @php--}}
{{--                    if(count($post->category) < 1){--}}
{{--                        $cat = new stdClass();--}}
{{--                        $cat->title_id = '-';--}}
{{--                    }else{--}}
{{--                        $cat = json_decode($post->category);--}}
{{--                        $cat = $cat[0];--}}
{{--                    }--}}
{{--                @endphp--}}
{{--                <tr>--}}
{{--                    <th>@lang('Category')</th>--}}
{{--                    <td>{{ ucfirst($cat->name)}}</td>--}}
{{--                </tr>--}}

{{--                <tr>--}}
{{--                    <th>@lang('Published at')</th>--}}
{{--                    <td>{{ $post->published_at ?? '-' }}</td>--}}
{{--                </tr>--}}

{{--                <tr>--}}
{{--                    <th>{{__('Featured Image')}}</th>--}}
{{--                    <td>--}}
{{--                        <img src="{{$post->featured_image()}}" alt="{{$post->title_id}}" class="img-thumbnail img-preview2">--}}
{{--                    </td>--}}
{{--                </tr>--}}

{{--                <tr>--}}
{{--                    <th>@lang('Share Count')</th>--}}
{{--                    <td>{{ $post->share_count }}</td>--}}
{{--                </tr>--}}

{{--                <tr>--}}
{{--                    <th>@lang('View Count')</th>--}}
{{--                    <td>{{ $post->view_count }}</td>--}}
{{--                </tr>--}}

{{--                <tr>--}}
{{--                    <th>@lang('Like Count')</th>--}}
{{--                    <td>{{ $post->like_count }}</td>--}}
{{--                </tr>--}}

            </table>
        </x-slot>

        <x-slot name="footer">
            <small class="float-end text-muted">
                <strong>@lang('Created at'):</strong> @displayDate($type->created_at) ({{ $type->created_at->diffForHumans() }}),
                <strong>@lang('Last Updated'):</strong> @displayDate($type->updated_at) ({{ $type->updated_at->diffForHumans() }})

                @if($type->trashed())
                    <strong>@lang('Deleted at'):</strong> @displayDate($type->deleted_at) ({{ $type->deleted_at->diffForHumans() }})
                @endif
            </small>
        </x-slot>
    </x-backend.card>
@endsection
