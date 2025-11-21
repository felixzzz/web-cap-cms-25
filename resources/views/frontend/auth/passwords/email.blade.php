@extends('layouts.auth')

@section('title', __('Reset Password'))

@section('content')
<div class="auth-content">
  <div class="auth-box">
    <x-forms.post :action="route('frontend.auth.password.email')" class="form w-100">
      <div class="auth-box-header">
        <h1>@lang('Forgot Password?')</h1>
        <p>Enter your email to reset your password</p>
      </div>

      <div class="fv-row mb-8 fv-plugins-icon-container fv-plugins-bootstrap5-row-valid">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="text" placeholder="Enter email" name="email" class="form-control bg-transparent" required autofocus autocomplete="email">
        </div>
        @error('email')
        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
      <div class="d-grid">
        <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
          <span class="indicator-label">@lang('Send Password Reset Link')</span>
        </button>
      </div>
    </x-forms.post>
  </div>
</div>
@endsection