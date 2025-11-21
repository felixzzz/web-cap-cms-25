@extends('layouts.app')

@section('title', __('Your password has expired.'))

@section('content')
<div class="auth-content">
  <div class="auth-box">
    <x-forms.patch :action="route('frontend.auth.password.expired.update')" class="form w-100">
      <div class="auth-box-header">
        <h1>@lang('Password Expired')</h1>
        <p>Your password has expired.</p>
      </div>

      <div class="fv-row mb-8" data-kt-password-meter="true">
        <div class="mb-1">
          <div class="position-relative mb-3">
            <input class="form-control bg-transparent @error('current_password') is-invalid @enderror" type="password" name="current_password" placeholder="{{ __('Current Password') }}" autofocus />
            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
              <i class="bi bi-eye-slash fs-2"></i>
              <i class="bi bi-eye fs-2 d-none"></i>
            </span>
          </div>
        </div>
        @error('current_password')
        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="fv-row mb-8" data-kt-password-meter="true">
        <div class="mb-1">
          <div class="position-relative mb-3">
            <input class="form-control bg-transparent @error('password') is-invalid @enderror" type="password" placeholder="Password" name="password" autocomplete="password" />
            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
              <i class="bi bi-eye-slash fs-2"></i>
              <i class="bi bi-eye fs-2 d-none"></i>
            </span>
          </div>
        </div>
        @error('password')
        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
      <div class="fv-row mb-8">
        <input placeholder="Confirm password" name="password_confirmation" type="password" autocomplete="new-password" class="form-control bg-transparent mb-1 @error('password') is-invalid @enderror" />
        <div class="text-muted">
          Use 8 character or more with unique combinations.
        </div>
      </div>
      <div class="d-grid">
        <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
          <span class="indicator-label">@lang('Update Password')</span>
        </button>
      </div>
      </x-forms.post>
  </div>
</div>
@endsection