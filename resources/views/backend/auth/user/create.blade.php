@inject('model', '\App\Domains\Auth\Models\User')

@extends('backend.layouts.app')

@section('title', __('Create User'))

@section('content')
    <x-forms.post :action="route('admin.auth.user.store')">
        <x-backend.card>
            <x-slot name="header">
                @lang('Create User')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="btn btn-sm btn-light" :href="route('admin.auth.user.index')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                <div x-data="{ userType: '{{ $model::TYPE_ADMIN }}' }">
                    <input type="hidden" name="type" value="{{ $model::TYPE_ADMIN }}" />

                    <div class="row mb-8">
                        <label for="name" class="col-md-3 form-label">@lang('Name')</label>

                        <div class="col-md-9">
                            <input type="text" name="name" class="form-control" placeholder="{{ __('Name') }}"
                                value="{{ old('name') }}" maxlength="100" required />
                        </div>
                    </div><!--form-group-->

                    <div class="row mb-8">
                        <label for="email" class="col-md-3 form-label">@lang('E-mail Address')</label>

                        <div class="col-md-9">
                            <input type="email" name="email" class="form-control"
                                placeholder="{{ __('E-mail Address') }}" value="{{ old('email') }}" maxlength="255"
                                required />
                        </div>
                    </div><!--form-group-->

                    <div class="row mb-8">
                        <label for="password" class="col-md-3 form-label">@lang('Password')</label>

                        <div class="col-md-9">
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="{{ __('Password') }}" maxlength="100" required autocomplete="new-password" />
                        </div>
                    </div><!--form-group-->

                    <div class="row mb-8">
                        <label for="password_confirmation" class="col-md-3 form-label">@lang('Password Confirmation')</label>

                        <div class="col-md-9">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" placeholder="{{ __('Password Confirmation') }}" maxlength="100"
                                required autocomplete="new-password" />
                        </div>
                    </div><!--form-group-->

                    <div class="row mb-8">
                        <label for="active" class="col-md-3 form-label">@lang('Active')</label>

                        <div class="col-md-9">
                            <div class="form-check">
                                <input name="active" id="active" class="form-check-input" type="checkbox" value="1"
                                    {{ old('active', true) ? 'checked' : '' }} />
                            </div><!--form-check-->
                        </div>
                    </div><!--form-group-->

                    <div x-data="{ emailVerified: true }">
                        <div>
                            <div class="row mb-8">
                                <label for="access_cms" class="col-md-3 form-label">@lang('Access CMS')</label>

                                <div class="col-md-9">
                                    <div class="form-check">
                                        <input type="checkbox" name="access_cms" id="access_cms" value="1"
                                            class="form-check-input" {{ old('access_cms') ? 'checked' : '' }} />
                                    </div><!--form-check-->
                                </div>
                            </div><!--form-group-->
                        </div>

                        <div class="row mb-8">
                            <label for="email_verified" class="col-md-3 form-label">@lang('E-mail Verified')</label>

                            <div class="col-md-9">
                                <div class="form-check">
                                    <input type="checkbox" name="email_verified" id="email_verified" value="1"
                                        class="form-check-input" x-on:click="emailVerified = !emailVerified" checked
                                        {{ old('email_verified') ? 'checked' : '' }} />
                                </div><!--form-check-->
                            </div>
                        </div><!--form-group-->

                        <div x-show="!emailVerified">
                            <div class="row mb-8">
                                <label for="send_confirmation_email" class="col-md-3 form-label">@lang('Send Confirmation E-mail')</label>

                                <div class="col-md-9">
                                    <div class="form-check">
                                        <input type="checkbox" name="send_confirmation_email" id="send_confirmation_email"
                                            value="1" class="form-check-input"
                                            {{ old('send_confirmation_email') ? 'checked' : '' }} />
                                    </div><!--form-check-->
                                </div>
                            </div><!--form-group-->
                        </div>



                        <div class="row mb-8">
                            <label for="role_id" class="col-md-3 form-label">@lang('Role')</label>

                            <div class="col-md-9">
                                <select name="role_id" required class="form-control">
                                    <option value="">@lang('- Select Role -')</option>
                                    @foreach ($roles->where('type', $model::TYPE_ADMIN) as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div><!--form-group-->
                    </div>

                </div>
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-end" type="submit">@lang('Create User')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection
