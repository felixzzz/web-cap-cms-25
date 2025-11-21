@extends('layouts.app')

@section('title', __('Verify Your E-mail Address'))

@section('content')
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
            <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                <div class="w-lg-500px p-10">
                    <x-forms.post :action="route('frontend.auth.verification.resend')" class="form w-100">
                        <div class="text-center mb-11">
                            <h1 class="text-dark fw-bolder mb-3">@lang('Verify Your E-mail Address')</h1>
                            <div class="text-gray-500 fw-semibold fs-6">@lang('Before proceeding, please check your email for a verification link.')</div>
                        </div>

                        <div class="d-grid mb-10">
                            <div class="text-center mb-5">
                                <div class="text-gray-500 fw-semibold fs-6">@lang('If you did not receive the email')</div>
                            </div>
                            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                                <span class="indicator-label">@lang('Click here to request another').</span>
                            </button>
                        </div>
                    </x-forms.post>
                </div>
            </div>
        </div>
        @include('frontend.auth.includes.logo')
    </div>
@endsection
