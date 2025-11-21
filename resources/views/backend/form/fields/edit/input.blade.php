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
                <x-forms.select label="Input Type" name="input" placeholder="Select an option" required="1" multiple="0" text="" hidden="0" required="1">
                    <option value="text" {{ $field->input === 'text' ? 'selected' : '' }}>Text</option>
                    <option value="email" {{ $field->input === 'email' ? 'selected' : '' }}>Email</option>
                    <option value="number" {{ $field->input === 'number' ? 'selected' : '' }}>Number</option>
                    <option value="phone" {{ $field->input === 'phone' ? 'selected' : '' }}>Phone</option>
                    <option value="file" {{ $field->input === 'file' ? 'selected' : '' }}>File</option>
                    <option value="image" {{ $field->input === 'image' ? 'selected' : '' }}>Image</option>
                </x-forms.select>
                <x-forms.text-input name="placeholder" required="0" label="Placeholder" placeholder="Full Name" value="{{ $field->placeholder }}"/>
                <x-forms.text-input name="class" required="0" label="Additional Class" placeholder="mb-4" value="{{ $field->class }}"/>
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
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Update Field')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection
