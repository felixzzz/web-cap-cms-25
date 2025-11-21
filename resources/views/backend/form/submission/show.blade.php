@extends('backend.layouts.app')

@section('title', 'Submission Details')

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Submission Details')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link icon="fa fa-left" class="btn btn-sm btn-outline" :href="route('admin.form.submission', $form)" :text="__('Back')" />
            &nbsp;
        </x-slot>

        <x-slot name="body">
            <table class="table table-hover">
                @foreach ($submission->fields as $field)
                    <tr>
                        <th>
                            <b>{{ ucwords($field->key) }}</b> <br>
                            <small>{{ $field->value }}</small>
                        </th>
                    </tr>
                @endforeach
            </table>
        </x-slot>

        <x-slot name="footer">
            <small class="float-start text-muted">
                <strong>@lang('Submited at'):</strong> @displayDate($form->created_at) ({{ $form->created_at->diffForHumans() }}),
            </small>
        </x-slot>
    </x-backend.card>
@endsection
