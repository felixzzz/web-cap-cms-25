@extends('layouts.app')
@section('content')
    <div class="page d-flex flex-row flex-column-fluid">
        @include('backend.includes.sidebar')
        <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
            {{-- @include('backend.includes.header') --}}
            <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                <div class="post d-flex flex-column-fluid" id="kt_post">
                    <div id="kt_content_container" class="container-xxl">
                        @include('includes.partials.messages')
                        @yield('page')
                    </div>
                </div>
            </div>

            <div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
                <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
                  <div class="text-dark order-2 order-md-1">
                    <span class="text-muted fw-semibold me-1">{{ date('Y') }}©</span>
                    <a href="#" target="_blank" class="text-white text-hover-primary">{{ config('app.name') }}</a>
                  </div>
                </div>
              </div>
        </div>
    </div>
@endsection
