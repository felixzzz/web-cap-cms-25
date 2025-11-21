@extends('layouts.auth')

@section('title', __('Login'))
@push('headers')
<style>
  .alert {
    display: none !important;
  }
</style>
@endpush
@section('content')
<div class="auth-content">
  <div class="auth-box">
    <x-forms.post :action="route('frontend.auth.login')" class="form w-100">
      <div class="auth-box-header login">
        <p>Chandra Asri</p>
        <h1>Anti CMS</h1>
      </div>
      <div class="fv-row mb-8 fv-plugins-icon-container fv-plugins-bootstrap5-row-valid">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="text" placeholder="Enter email" name="email" autocomplete="off" class="form-control bg-transparent" required>
        </div>

        @error('email')
        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
      <div class="fv-row mb-3 fv-plugins-icon-container fv-plugins-bootstrap5-row-valid">
        <div class="form-group">
          <label for="email">Password</label>
          <input type="password" placeholder="Enter password" name="password" autocomplete="off" class="form-control bg-transparent" required>
        </div>
        @error('password')
        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="auth-box-forgot">
        @if (Route::has('frontend.auth.password.request'))
        <x-utils.link :href="route('frontend.auth.password.request')" class="link-primary" :text="__('Forgot password?')" />
        @endif
      </div>
      <div class="d-grid">
        <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
          <span class="indicator-label">Login</span>
        </button>
      </div>
    </x-forms.post>
  </div>
  <div class="auth-footer">© 2025 <a href="https://antikode.com" target="_blank">Antikode</a></div>
</div>

{{-- <div class="d-flex flex-column flex-lg-row flex-column-fluid">--}}
{{-- <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">--}}
{{-- <div class="d-flex flex-center flex-column flex-lg-row-fluid">--}}
{{-- <div class="w-lg-500px p-10">--}}

{{-- <div class="text-center mb-11">--}}
{{-- <h1 class="text-dark fw-bolder mb-3">Login</h1>--}}
{{-- <div class="text-gray-500 fw-semibold fs-6">Access your account.</div>--}}
{{-- </div>--}}



{{-- <div class="d-grid mb-10">--}}
{{-- <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">--}}
{{-- <span class="indicator-label">Login</span>--}}
{{-- </button>--}}
{{-- </div>--}}
{{-- @if (config('boilerplate.access.user.registration'))--}}
{{-- <div class="text-gray-500 text-center fw-semibold fs-6">--}}
{{-- Don't have account? <x-utils.link :href="route('frontend.auth.register')" class="link-primary" :text="__('Register')" />--}}
{{-- </div>--}}
{{-- @endif--}}

{{-- </div>--}}
{{-- </div>--}}
{{-- </div>--}}
{{-- @include('frontend.auth.includes.logo')--}}
{{-- </div>--}}
@endsection