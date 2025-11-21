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
                <x-utils.link class="card-header-action" :href="route('admin.form.field', $field->form)" :text="__('Back')" />
            </x-slot>

            <x-slot name="body">
                <x-forms.text-input name="label" required="1" label="Label" placeholder="Full Name" value="{{ $field->label }}"/>
                <x-forms.text-input name="placeholder" required="0" label="Placeholder" placeholder="Full Name" value="{{ $field->placeholder }}"/>
                <x-forms.text-input name="class" required="0" label="Additional Class" placeholder="mb-4" value="{{ $field->class }}"/>
                <div class="row">
                    <div class="col-md-6">
                        <x-forms.text-input name="options[rows]" type="number" required="0" label="Rows" value="{{ $options['rows'] }}" placeholder="Any number for rows"/>
                    </div>
                    <div class="col-md-6">
                        <x-forms.text-input name="options[cols]" type="number" required="0" label="Cols" value="{{ $options['cols'] }}" placeholder="Any number for cols"/>
                    </div>
                </div>
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
