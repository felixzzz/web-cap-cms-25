@extends('layouts.app')

@section('title', __('Register'))

@section('content')
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
            <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                <div class="w-lg-500px p-10">
                    <x-forms.post :action="route('frontend.auth.register')" class="form w-100">
                        <div class="text-center mb-11">
                            <h1 class="text-dark fw-bolder mb-3">Register Account</h1>
                            <div class="text-gray-500 fw-semibold fs-6">Please input your details below.</div>
                        </div>
                        <div class="fv-row mb-8">
                            <input type="text" placeholder="Your name" name="name" autocomplete="off" value="{{ old('name') }}" class="form-control bg-transparent @error('name') is-invalid @enderror" required/>
                            @error('name')
                            <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- <div class="fv-row mb-8">
                            <input type="text" placeholder="Your username" name="username" autocomplete="off" value="{{ old('username') }}" class="form-control bg-transparent @error('name') is-invalid @enderror" required/>
                            @error('name')
                            <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> --}}
                        <div class="fv-row mb-8">
                            <input type="email" placeholder="Your email address" name="email" autocomplete="off" value="{{ old('email') }}" class="form-control bg-transparent @error('email') is-invalid @enderror" required/>
                            @error('email')
                            <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="fv-row mb-8" data-kt-password-meter="true">
                            <div class="mb-1">
                                <div class="position-relative mb-3">
                                    <input class="form-control bg-transparent @error('password') is-invalid @enderror" type="password" placeholder="Password" name="password" autocomplete="off" />
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
                            <input placeholder="Confirm password" name="password_confirmation" type="password" autocomplete="off" class="form-control bg-transparent mb-1 @error('password') is-invalid @enderror" />
                            <div class="text-muted">
                                Use 8 character or more with unique combinations.
                            </div>
                        </div>
                        <div class="fv-row mb-8">
                            <label class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="terms" value="1" />
                                <span class="form-check-label fw-semibold text-gray-700 fs-base ms-1">I agree with the
										<a href="#" class="ms-1 link-primary">Terms & Conditions</a>.</span>
                            </label>
                            @error('terms')
                            <div class="fv-plugins-message-container invalid-feedback">
                                <div data-field="toc" data-validator="notEmpty">
                                    {{ $message }}
                                </div>
                            </div>
                            @enderror
                        </div>
                        <div class="d-grid mb-10">
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Register</span>
                            </button>
                        </div>
                        <div class="text-gray-500 text-center fw-semibold fs-6">Already have account?
                            <x-utils.link :href="route('frontend.auth.login')" class="link-primary fw-semibold" :text="__('Login')" />
                        </div>
                    </x-forms.post>
                </div>
            </div>
        </div>
        @include('frontend.auth.includes.logo')
    </div>
@endsection
