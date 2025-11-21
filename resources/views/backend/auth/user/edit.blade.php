@inject('model', '\App\Domains\Auth\Models\User')

@extends('backend.layouts.app')

@section('title', __('Update User'))

@section('content')
    <x-forms.patch :action="route('admin.auth.user.update', $user)" class="card mb-5 mb-xxl-8">
        <x-backend.card>
            <x-slot name="header">
                @lang('Update User')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="btn btn-sm btn-light" :href="route('admin.auth.user.index')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                <div x-data="{userType : '{{ $user->type }}'}">
                    <input type="hidden" name="type" value="{{ $user->type }}" />

                    <x-forms.text-input
                            name="name"
                            label="Name"
                            value="{{ old('name') ?? $user->name }}"
                            isSide="1"
                            text=""
                            required="1"/>

                    <x-forms.text-input
                            name="email"
                            label="E-mail Address"
                            value="{{ old('email') ?? $user->email }}"
                            isSide="1"
                            type="email"
                            required="1"/>

                    <div class="row mb-8">
                        <label for="role_id" class="col-md-3 form-label">@lang('Role')</label>

                        <div class="col-md-9">
                            <select name="role_id" required class="form-control">
                                <option value="">@lang('- Select Role -')</option>
                                @foreach($roles->where('type', $model::TYPE_ADMIN) as $role)
                                    <option value="{{$role->id}}" {{in_array($role->id, $user->roles->modelKeys(), true) ? 'selected' : ''}}>{{$role->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div><!--form-group-->
                    <div>
                        <div class="row mb-8">
                            <label for="access_cms" class="col-md-3 form-label">@lang('Access CMS')</label>

                            <div class="col-md-9">
                                <div class="form-check">
                                    <input
                                        type="checkbox"
                                        name="access_cms"
                                        id="access_cms"
                                        value="1"
                                        class="form-check-input"
                                        {{ $user->access_cms ? 'checked' : '' }} />
                                </div><!--form-check-->
                            </div>
                        </div><!--form-group-->
                    </div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-end" type="submit">@lang('Update User')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.patch>
@endsection
