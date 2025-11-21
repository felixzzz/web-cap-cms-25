@extends('backend.layouts.app')

@section('title', 'Detail ' . ucfirst($post->type))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Detail ' . ucfirst($post->type))
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action me-2" :href="route('admin.dynamic', ['type' => $type])" :text="__('Back')" />
            <x-utils.edit-button class="card-header-action" :href="route('admin.dynamic.edit', ['post' => $post])" :text="__('Edit')" />
        </x-slot>

        <x-slot name="body">
            <table class="table table-hover">
                <tr>
                    <th>@lang('Title')</th>
                    <td>{{ $post->title }}</td>
                </tr>

                <tr>
                    <th>@lang('Slug')</th>
                    <td>{{ $post->slug }}</td>
                </tr>

                <tr>
                    <th>@lang('Content')</th>
                    <td>{!! clean($post->content) !!}</td>
                </tr>

                <tr>
                    <th>@lang('Author')</th>
                    <td>{{ $post->user->name }}</td>
                </tr>

                <tr>
                    <th>@lang('Status')</th>
                    <td>{{ ucfirst($post->status) }}</td>
                </tr>

                <tr>
                    <th>@lang('Published at')</th>
                    <td>@displayDate($post->published_at)</td>
                </tr>

                <tr>
                    <th>@lang('Parent')</th>
                    <td>
                        @if ($parent != null)
                            <a href="{{ route('admin.dynamic.show', $parent) }}"
                                target="_blank">{{ $parent->title ?? '-' }}</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>{{ __('Featured Image') }}</th>
                    <td>
                        <img src="{{ $post->featured_image() }}" alt="{{ $post->title_id }}"
                            class="img-thumbnail img-preview2">
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
                    <th>@lang('Excerpt')</th>
                    <td>{{ $post->excerpt ?? '-' }}</td>
                </tr>
                <tr>
                    <th>@lang('Meta Title')</th>
                    <td>{{ $post->meta_title ?? '-' }}</td>
                </tr>
                <tr>
                    <th>@lang('Meta Description')</th>
                    <td>{{ $post->meta_description ?? '-' }}</td>
                </tr>
                <tr>
                    <th>@lang('Meta Keyword')</th>
                    <td>{{ $post->meta_keyword ?? '-' }}</td>
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
    @if ($components)
        <x-backend.card>
            <x-slot name="header">
                @lang('Custom Fields')
            </x-slot>
            <x-slot name="body">
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
            </x-slot>
        </x-backend.card>
    @endif
@endsection
