@extends('backend.layouts.app')

@section('title')

@section('breadcrumb-links')
@endsection

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Whistleblowing Submission')
        </x-slot>

        <x-slot name="body">
            <livewire:backend.contact.whistleblowingsubmit-table/>
        </x-slot>
    </x-backend.card>
@endsection
