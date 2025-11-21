@extends('layouts.app')

@section('title', __('My Account'))

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <x-frontend.card>
                    <x-slot name="header">
                        @lang('My Account')
                    </x-slot>

                    <x-slot name="headerActions">
                        <x-utils.link class="btn btn-sm btn-outline" :href="route('admin.dashboard')" :text="__('Dashboard')" />
                    </x-slot>

                    <x-slot name="body">

                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <x-utils.link :text="__('My Profile')" class="nav-link active" id="my-profile-tab"
                                    data-bs-toggle="tab" data-bs-target="#my-profile" href="#my-profile" role="tab"
                                    aria-controls="my-profile" aria-selected="true" />

                                <x-utils.link :text="__('Edit Information')" class="nav-link" id="information-tab" data-toggle="pill"
                                    href="#information" data-bs-toggle="tab" data-bs-target="#information" role="tab"
                                    aria-controls="information" aria-selected="false" />

                                @if (!$logged_in_user->isSocial())
                                    <x-utils.link :text="__('Password')" class="nav-link" id="password-tab" data-toggle="pill"
                                        href="#password" data-bs-toggle="tab" data-bs-target="#password" role="tab"
                                        aria-controls="password" aria-selected="false" />
                                @endif

                                <x-utils.link :text="__('Two Factor Authentication')" class="nav-link" id="two-factor-authentication-tab"
                                    data-toggle="pill" href="#two-factor-authentication" data-bs-toggle="tab"
                                    data-bs-target="#two-factor-authentication" role="tab"
                                    aria-controls="two-factor-authentication" aria-selected="false" />
                            </div>
                        </nav>

                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade pt-3 show active" id="my-profile" role="tabpanel"
                                aria-labelledby="my-profile-tab">
                                @include('frontend.user.account.tabs.profile')
                            </div><!--tab-profile-->

                            <div class="tab-pane fade pt-3" id="information" role="tabpanel"
                                aria-labelledby="information-tab">
                                @include('frontend.user.account.tabs.information')
                            </div><!--tab-information-->

                            @if (!$logged_in_user->isSocial())
                                <div class="tab-pane fade pt-3" id="password" role="tabpanel"
                                    aria-labelledby="password-tab">
                                    @include('frontend.user.account.tabs.password')
                                </div><!--tab-password-->
                            @endif

                            <div class="tab-pane fade pt-3" id="two-factor-authentication" role="tabpanel"
                                aria-labelledby="two-factor-authentication-tab">
                                @include('frontend.user.account.tabs.two-factor-authentication')
                            </div><!--tab-information-->
                        </div><!--tab-content-->
                    </x-slot>
                </x-frontend.card>
            </div><!--col-md-10-->
        </div><!--row-->
    </div><!--container-->
@endsection

@push('scripts')
    <script>
        /**
         * Place any jQuery/helper plugins in here.
         */
        $(function() {
            // Remember tab on page load
            $('a[data-toggle="tab"], a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
                let hash = $(e.target).attr('href');
                history.pushState ? history.pushState(null, null, hash) : location.hash = hash;
            });

            let hash = window.location.hash;
            if (hash) {
                $('.nav-link[href="' + hash + '"]').tab('show');
            }

            // Enable tooltips everywhere
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
