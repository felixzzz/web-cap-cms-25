@extends('backend.layouts.app')

@section('title', __('Change Password for :name', ['name' => $user->name]))

@section('content')
    <x-forms.patch :action="route('admin.auth.user.change-password.update', $user)" class="card mb-5 mb-xxl-8">
        <x-backend.card>
            <x-slot name="header">
                @lang('Change Password for :name', ['name' => $user->name])
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="btn btn-sm btn-light" :href="route('admin.auth.user.index')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                <x-forms.text-input
                    name="password"
                    label="Password"
                    value=""
                    isSide="1"
                    type="password"
                    required="1"/>

                <x-forms.text-input
                    name="password_confirmation"
                    label="Password Confirmation"
                    value=""
                    isSide="1"
                    type="password"
                    required="1"/>

            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Update')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.patch>
@endsection
