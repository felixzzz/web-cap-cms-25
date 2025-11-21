@extends('backend.layouts.app')

@section('title', __('View Category'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('View Category')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.document-categories.index', $template)" :text="__('Back')" />
        </x-slot>

        <x-slot name="body">
            <table class="table table-hover">
                <tr>
                    <th>@lang('Title')</th>
                    <td>{{ $category->name }}</td>
                </tr>

                <tr>
                    <th>@lang('Title EN')</th>
                    <td>{{ $category->name_en }}</td>
                </tr>

                <tr>
                    <th>@lang('Description')</th>
                    <td>{{ $category->description }}</td>
                </tr>

                <tr>
                    <th>@lang('Description EN')</th>
                    <td>{{ $category->description_en }}</td>
                </tr>
                </table>
        </x-slot>

        <x-slot name="footer">
            <small class="float-end text-muted">
                <strong>@lang('Created at'):</strong> @displayDate($category->created_at) ({{ optional($category->created_at)->diffForHumans() }}),
                <strong>@lang('Last Updated'):</strong> @displayDate($category->updated_at) ({{ optional($category->updated_at)->diffForHumans() }})

                @if($category->trashed())
                    <strong>@lang('Deleted at'):</strong> @displayDate($category->deleted_at) ({{ optional($category->deleted_at)->diffForHumans() }})
                @endif
            </small>
        </x-slot>
    </x-backend.card>
@endsection
