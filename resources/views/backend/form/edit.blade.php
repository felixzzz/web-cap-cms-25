@extends('backend.layouts.app')

@section('title', __('Edit Form'))

@section('content')
    <x-forms.post :action="route('admin.form.update', $form)">
        @csrf
        @method('PATCH')
        <x-backend.card>
            <x-slot name="header">
                @lang('Edit Form')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.form')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                <x-forms.text-input name="name" required="1" label="Form Name" placeholder="Input name for the form" value="{{ $form->name }}"/>
                <div class="d-flex flex-stack mb-8">
                    <div class="me-5">
                        <label class="fs-6 fw-semibold">Auto Reply</label>

                        <div class="fs-7 fw-semibold text-muted">Give the user auto feedback once they have submitted the form.</div>
                    </div>
                    <label class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name="auto_reply" {{ $form->auto_reply ? 'checked' : '' }}>
                    </label>
                </div>
                @if($form->auto_reply)
                    <div class="row">
                        <div class="col-md-6">
                            <x-forms.text-input name="admin_email" type="email" required="0" label="Admin Email" placeholder="Admin or staff email" value="{{ $form->admin_email }}" text="This email will be the point where to notify the admin about new incoming submission."/>
                        </div>
                        <div class="col-md-6">
                            <x-forms.text-input name="subject" required="0" label="Subject Message" placeholder="Auto-Reply - Thank you for your message!" value="{{ $form->subject }}" text="This is general subject message for user."/>
                        </div>
                    </div>
                    <x-forms.ckeditor5-editor name="message" label="Content Message (to User)" required="0" hidden="0" value="{{ $form->message }}" text="Make sure you have 1 fields input with the type of email."/>
                @endif
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Update Form')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection
