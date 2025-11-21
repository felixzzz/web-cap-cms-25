@extends('backend.layouts.app')

@section('title', 'Form Details')

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Form Details')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link icon="fa fa-left" class="btn btn-sm btn-outline" :href="route('admin.form')" :text="__('Back')" />
            @if ($logged_in_user->can('admin.access.form.edit'))
                <x-utils.edit-button :href="route('admin.form.edit', $form)" />
            @endif
        </x-slot>

        <x-slot name="body">
            <table class="table table-hover">
                <tr>
                    <th>@lang('Name')</th>
                    <td>{{ $form->name }}</td>
                </tr>

                <tr>
                    <th>@lang('Total Fields')</th>
                    <td>{{ $form->field_count }} Fields</td>
                </tr>
                <tr>
                    <th>@lang('Auto Reply')</th>
                    <td>{{ $form->auto_reply ? 'Yes' : 'No' }}</td>
                </tr>
                @if ($form->auto_reply)
                    <tr>
                        <th>@lang('Auto Reply - Admin Email')</th>
                        <td>{{ $form->admin_email }}</td>
                    </tr>
                    <tr>
                        <th>@lang('Subject')</th>
                        <td>{!! $form->subject !!}</td>
                    </tr>
                    <tr>
                        <th>@lang('Message')</th>
                        <td>{!! $form->message !!}</td>
                    </tr>
                @endif
            </table>
        </x-slot>

        <x-slot name="footer">
            <small class="float-start text-muted">
                <strong>@lang('Created at'):</strong> @displayDate($form->created_at) ({{ $form->created_at->diffForHumans() }}),
                <br>
                <strong>@lang('Last Updated'):</strong> @displayDate($form->updated_at) ({{ $form->updated_at->diffForHumans() }})
            </small>
        </x-slot>
    </x-backend.card>
@endsection
