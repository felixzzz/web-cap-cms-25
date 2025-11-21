@extends('backend.layouts.app')

@section('title', 'Detail ' . ucfirst($post->type))

@push('headers')
    <style>
        .content img {
            max-height: 350px;
        }
    </style>
@endpush
@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Detail ' . ucfirst($post->type))
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action me-2" :href="route('admin.post.index', ['type' => $type])" :text="__('Back')" />
            <x-utils.edit-button class="card-header-action" :href="route('admin.post.edit', ['post' => $post])" :text="__('Edit')" />
        </x-slot>

        <x-slot name="body">
            <table class="table table-hover">
                <tr>
                    <th>@lang('ID')</th>
                    <td>{{ $post->id }}</td>
                </tr>

                <tr>
                    <th>@lang('Sort Order')</th>
                    <td>{{ $post->sort }}</td>
                </tr>

                <tr>
                    <th>@lang('Title ID')</th>
                    <td>{{ $post->title }}</td>
                </tr>
                <tr>
                    <th>@lang('Title EN')</th>
                    <td>{{ $post->title_en }}</td>
                </tr>

                <tr>
                    <th>@lang('Slug ID')</th>
                    <td>{{ $post->slug }}</td>
                </tr> <tr>
                    <th>@lang('Slug EN')</th>
                    <td>{{ $post->slug_en }}</td>
                </tr>

                <tr>
                    <th>@lang('Content')</th>
                    <td>
                        <div class="content">
                            {!! clean($post->content) !!}
                        </div>
                    </td>
                </tr>

                <tr>
                    <th>@lang('Author')</th>
                    <td>{{ $post->user->name }}</td>
                </tr>

                <tr>
                    <th>@lang('Status')</th>
                    <td>{{ ucfirst($post->status) }}</td>
                </tr>

                @php
                    if (count($post->category) < 1) {
                        $cat = new stdClass();
                        $cat->title_id = '-';
                        $cat->name = '-';
                    } else {
                        $cat = json_decode($post->category);
                        $cat = $cat[0];
                    }
                @endphp
                <tr>
                    <th>@lang('Category')</th>
                    <td>{{ ucfirst($cat->name) }}</td>
                </tr>

                <tr>
                    <th>@lang('Published at')</th>
                    <td>{{ $post->published_at ?? '-' }}</td>
                </tr>

                <tr>
                    <th>{{ __('Featured Image') }}</th>
                    <td>
                        <img src="{{ $post->featured_image() }}" alt="{{ $post->title_id }}"
                            class="img-thumbnail img-preview2" style="max-height: 250px;">
                    </td>
                </tr>

                <tr>
                    <th>@lang('Share Count')</th>
                    <td>{{ $post->share_count }}</td>
                </tr>

                <tr>
                    <th>@lang('View Count')</th>
                    <td>{{ $post->view_count }}</td>
                </tr>

                <tr>
                    <th>@lang('Like Count')</th>
                    <td>{{ $post->like_count }}</td>
                </tr>

                <tr>
                    <th>@lang('Meta Title')</th>
                    <td>{{ $post->meta_title }}</td>
                </tr>

                <tr>
                    <th>@lang('Meta Description')</th>
                    <td>{{ $post->meta_description }}</td>
                </tr>

                <tr>
                    <th>@lang('Meta Keyword')</th>
                    <td>{{ $post->meta_keyword }}</td>
                </tr>

            </table>
        </x-slot>

        <x-slot name="footer">
            <small class="float-end text-muted">
                <strong>@lang('Created at'):</strong> @displayDate($post->created_at) ({{ $post->created_at->diffForHumans() }}),
                <strong>@lang('Last Updated'):</strong> @displayDate($post->updated_at) ({{ $post->updated_at->diffForHumans() }})

                @if ($post->trashed())
                    <strong>@lang('Deleted at'):</strong> @displayDate($post->deleted_at) ({{ $post->deleted_at->diffForHumans() }})
                @endif
            </small>
        </x-slot>
    </x-backend.card>
    </br>
    @if (false)
        <x-backend.card>
            <x-slot name="header">
                @lang('View Page Metas')
            </x-slot>
            <x-slot name="body">
                @if ($components)
                    <div id="app">
                        @foreach ($components as $component)
                            @include('components.backend.component-show-v2', [
                                'component' => $component,
                                'meta' => $meta,
                                'loopIndex' => $loop->index,
                                'postId' => $post->id,
                            ])
                        @endforeach
                    </div>
                @endif
            </x-slot>
        </x-backend.card>
    @endif
@endsection
