@extends('backend.layouts.app')

@section('title', __('Update Field'))

@section('content')
    <x-forms.post :action="route('admin.form.field.update', $field)">
        @method('PATCH')
        <x-backend.card>
            <x-slot name="header">
                @lang('Update Field')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.form')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                <div class="row">
                    <div class="col-md-6">
                        <x-forms.text-input name="label" required="1" label="Label" placeholder="Full Name" value="{{ $field->label }}"/>
                    </div>
                    <div class="col-md-6">
                        <x-forms.text-input name="placeholder" required="0" label="Placeholder" placeholder="Select the options.." value="{{ $field->placeholder }}"/>
                    </div>
                </div>
                <x-forms.text-input name="class" required="0" label="Additional Class" placeholder="mb-4" value="{{ $field->class }}"/>
                <x-forms.textarea-input name="options" required="1" hidden="0" label="Options" value="{{ $options }}" placeholder="value:key" text="Separate with a new line."/>
                <div class="d-flex flex-stack mb-8">
                    <div class="me-5">
                        <label class="fs-6 fw-semibold">Required</label>
                        <div class="fs-7 fw-semibold text-muted">This field will be required</div>
                    </div>
                    <label class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name="is_required" {{ $field->is_required ? 'checked' : '' }}>
                    </label>
                </div>
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Update field')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection
