@extends('layouts.app')

@section('title', __('Please confirm your password before continuing.'))

@section('content')
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
            <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                <div class="w-lg-500px p-10">
                    <x-forms.post :action="route('frontend.auth.password.confirm')" class="form w-100">
                        <div class="text-center mb-11">
                            <h1 class="text-dark fw-bolder mb-3">@lang('Please confirm your password before continuing.')</h1>
                        </div>

                        <div class="fv-row mb-3 fv-plugins-icon-container fv-plugins-bootstrap5-row-valid">
                            <input type="password" placeholder="Password" name="password" autocomplete="current-password" class="form-control bg-transparent" required>
                            @error('password')
                            <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-grid mb-10">
                            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                                <span class="indicator-label">@lang('Confirm Password')</span>
                            </button>
                        </div>
                    </x-forms.post>
                </div>
            </div>
        </div>
        @include('frontend.auth.includes.logo')
    </div>
@endsection
