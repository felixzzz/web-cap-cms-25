@extends('backend.layouts.app')

@section('title', __('View ' . $heading))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('View ' . $heading)
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link icon="fa fa-left" class="btn btn-sm btn-outline" :href="route('admin.tag.index')" :text="__('Back')" />
            &nbsp;
            <x-utils.edit-button :href="route('admin.tag.edit', $model)" />
        </x-slot>

        <x-slot name="body">
            <table class="table table-hover">
                <tr>
                    <th> Id </th>
                    <td> {{ $model->id }} </td>
                </tr>
                <tr>
                    <th> Name </th>
                    <td> {{ $model->name }} </td>
                </tr>
                <tr>
                    <th> Slug </th>
                    <td> {{ $model->slug }} </td>
                </tr>
                <tr>
                    <th> Order Column </th>
                    <td> {{ $model->order_column }} </td>
                </tr>
            </table>
        </x-slot>

        <x-slot name="footer">
            <small class="float-right text-muted">
                @if ($model->created_at)
                    <strong>@lang('Account Created'):</strong> @displayDate($model->created_at) ({{ $model->created_at->diffForHumans() }}),
                @endif
                @if ($model->updated_at)
                    <strong>@lang('Last Updated'):</strong> @displayDate($model->updated_at) ({{ $model->updated_at->diffForHumans() }})
                @endif
            </small>
        </x-slot>
    </x-backend.card>
@endsection
