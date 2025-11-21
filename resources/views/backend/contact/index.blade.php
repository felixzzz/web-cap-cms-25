@extends('backend.layouts.app')

@section('title')

@section('breadcrumb-links')
@endsection

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Contact Us Submission')
        </x-slot>

        <x-slot name="body">
            <livewire:backend.contact.contact-table/>
        </x-slot>
    </x-backend.card>
@endsection
