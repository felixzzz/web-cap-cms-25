@extends('backend.layouts.app')

@section('title', __('Submission - '.$form->name))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Submission - '.$form->name)
        </x-slot>

        <x-slot name="body">
            <livewire:backend.form.submission-table form="{{ $form->id }}"/>
        </x-slot>
    </x-backend.card>
@endsection
