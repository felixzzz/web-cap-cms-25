@extends('backend.layouts.app')

@section('title', __('View Field'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('View Field')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link icon="fa fa-left" class="btn btn-sm btn-outline" :href="route('admin.form.field', $model->form)" :text="__('Back')" />
            @if ($logged_in_user->can('admin.access.form.fields.edit'))
                <x-utils.edit-button :href="route('admin.form.field.edit', $model)" />
            @endif
        </x-slot>

        <x-slot name="body">
            <table class="table table-hover">
                <tr>
                    <th>Id</th>
                    <td>: {{ $model->id }} </td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>: {{ $model->name }} </td>
                </tr>
                <tr>
                    <th>Label</th>
                    <td>: {{ $model->label }} </td>
                </tr>
                <tr>
                    <th>Is Required</th>
                    <td>: {{ $model->is_required ? 'Yes' : 'No' }} </td>
                </tr>
                <tr>
                    <th>Field type</th>
                    <td>: {{ ucwords($model->type) }} {{ $model->type === 'input' ? '- ' . ucwords($model->input) : '' }}
                    </td>
                </tr>
            </table>
        </x-slot>

        <x-slot name="footer">
            <small class="float-right text-muted">
                @if ($model->created_at)
                    <strong>@lang('Field Created'):</strong> @displayDate($model->created_at) ({{ $model->created_at->diffForHumans() }}),
                    <br>
                @endif
                @if ($model->updated_at)
                    <strong>@lang('Last Updated'):</strong> @displayDate($model->updated_at) ({{ $model->updated_at->diffForHumans() }})
                @endif
            </small>
        </x-slot>
    </x-backend.card>
@endsection
