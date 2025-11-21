@extends('backend.layouts.app')

@section('title', __('Dashboard'))

@section('content')
    <x-backend.card>
        <x-slot name="frame">
            <iframe width="100%" height="1268" src="{{ Config::get('app.datalooker') }}"></iframe>
        </x-slot>
    </x-backend.card>
@endsection
