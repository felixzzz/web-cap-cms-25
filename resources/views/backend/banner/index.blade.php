@extends('backend.layouts.app')

@section('title', __('Banners'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            <div class="d-flex flex-column">
                @php $subText = ''; @endphp
                @if (($position ?? 'article') == 'home')
                    <span>@lang('Banners for Homepage')</span>
                    @php $subText = __('Setup your banner for homepage here'); @endphp
                @elseif (($position ?? 'article') == 'pages')
                    <span>@lang('Banners for Pages')</span>
                    @php $subText = __('Setup your banner for pages here'); @endphp
                @else
                    <span>@lang('Banners for Article')</span>
                    @php $subText = __('Setup your banner for news and blog here'); @endphp
                @endif
                <span class="text-muted small fw-normal mt-1" style="font-size: 0.8rem;">
                    {{ $subText }}
                </span>
            </div>
        </x-slot>

        <x-slot name="headerActions">
            @php
                $createRoute = route('admin.banner.create');
                $createText = __('Create Articles Banner');
                $p = $position ?? 'article';

                if ($p == 'home') {
                    $createRoute = route('admin.banner.home.create');
                    $createText = __('Create Homepage Banner');
                } elseif ($p == 'pages') {
                    $createRoute = route('admin.banner.pages.create');
                    $createText = __('Create Pages Banner');
                }
            @endphp
            <x-utils.link icon="fa fa-plus" class="btn btn-sm btn-primary" :href="$createRoute" :text="$createText" />
        </x-slot>

        <x-slot name="body">
            <livewire:backend.banner.banner-group-table :position="$position" />
            <livewire:backend.banner.banner-active-list :position="$position" />
            @if (($position ?? 'article') == 'home')
                <livewire:backend.banner.banner-home-embed />
            @elseif (($position ?? 'article') == 'pages')
                <livewire:backend.banner.banner-pages-embed />
            @else
                <livewire:backend.banner.banner-embed />
            @endif
        </x-slot>
    </x-backend.card>
@endsection
