@extends('layouts.app')

@section('title', __('Two Step Verification'))

@section('content')
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
            <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                <div class="w-lg-500px p-10">
                    <form class="form w-100 mb-13" novalidate="novalidate" method="post">
                        @csrf
                        <!--begin::Icon-->
                        <div class="text-center mb-10">
                            <img alt="Logo" class="mh-125px" src="{{ asset('media/svg/misc/smartphone-2.svg') }}" />
                        </div>
                        <!--end::Icon-->
                        <!--begin::Heading-->
                        <div class="text-center mb-10">
                            <!--begin::Title-->
                            <h1 class="text-dark mb-3">{{ __('Two Step Verification') }}</h1>
                            <!--end::Title-->
                            <!--begin::Sub-title-->
                            <div class="text-muted fw-semibold fs-5 mb-5">{{ trans('two-factor::messages.continue') }}</div>
                            <!--end::Sub-title-->
                        </div>
                        <!--end::Heading-->
                        <!--begin::Section-->
                        <div class="mb-10">
                            <!--begin::Label-->
                            <div class="fw-bold text-start text-dark fs-6 mb-1 ms-1">Type your digit security code</div>
                            <!--end::Label-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-8">
                                <input type="text" name="{{ $input }}" id="{{ $input }}"
                                        class="@error($input) is-invalid @enderror form-control form-control-lg bg-transparent"
                                        minlength="6" placeholder="123456" required>
                                @error($input)
                                <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!--begin::Input group-->
                        </div>
                        <!--end::Section-->
                        <div class="d-grid mb-10">
                            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                                <span class="indicator-label">{{ trans('two-factor::messages.confirm') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('frontend.auth.includes.logo')
    </div>
@endsection
@push('scripts')

<script src="{{ asset('js/custom/authentication/sign-in/two-steps.js') }}"></script>

@endpush

{{-- @section('card-body')
    <form method="post">
        @csrf
        <p class="text-center">asdasd
            {{ trans('two-factor::messages.continue') }}
        </p>
        <div class="form-row justify-content-center py-3">
            @if($errors->isNotEmpty())
                <div class="col-12 alert alert-danger pb-0">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="col-sm-8 col-8 mb-3">
                <input type="text" name="{{ $input }}" id="{{ $input }}"
                       class="@error($input) is-invalid @enderror form-control form-control-lg"
                       minlength="6" placeholder="123456" required>
            </div>
            <div class="w-100"></div>
            <div class="col-auto mb-3">
                <button type="submit" class="btn btn-primary btn-lg">
                    {{ trans('two-factor::messages.confirm') }}
                </button>
            </div>
        </div>
    </form>
@endsection --}}
