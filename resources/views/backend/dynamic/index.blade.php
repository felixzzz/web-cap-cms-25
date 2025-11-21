@extends('backend.layouts.app')

@section('title', 'Page')

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Business Page')
        </x-slot>

        @if ($logged_in_user->hasAllAccess())
            <x-slot name="headerActions">
                <x-forms.modal-button class="btn btn-sm btn-primary " id-modal="create_page" title="Create a New Page"
                    label="Create Page" icon="fa fa-plus" description="Please determine the structural item below.">
                    <div class="mh-475px scroll-y me-n7 pe-7">
                        @foreach ($templates as $item)
                            <div class="border border-hover-primary p-7 rounded mb-7">
                                <div class="d-flex flex-stack mb-3">
                                    <div class="d-flex">
                                        <div class="">
                                            <div class="d-flex align-items-center">
                                                <span
                                                    class="text-dark fw-bold text-hover-primary fs-5 me-4">{{ $item['label'] }}</span>
                                            </div>
                                            @if (count($item['components']) > 0)
                                                <span
                                                    class="badge badge-light-success align-items-center fs-8 shrink-0 fw-semibold">
                                                    {{ count($item['components']) }} Components
                                                </span>
                                            @else
                                                <span class="text-muted fw-semibold mb-3">ID:
                                                    <b>{{ $item['name'] }}</b></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div clas="d-flex">
                                        <div class="text-end pb-3">
                                            <a href="{{ route('admin.dynamic.create', ['template' => $item['name']]) }}"
                                                class="btn btn-sm btn-primary">Select</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-0">
                                    <div class="d-flex flex-column">
                                        <p class="text-gray-700 fw-semibold fs-6 mb-4">
                                            {{ $item['description'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-forms.modal-button>
            </x-slot>
        @endif

        <x-slot name="body">
            <livewire:backend.page.dynamic-table />
        </x-slot>
    </x-backend.card>
@endsection
