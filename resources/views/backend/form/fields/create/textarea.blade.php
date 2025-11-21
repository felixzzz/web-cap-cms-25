@extends('backend.layouts.app')

@section('title', __('Create Field'))

@section('content')
    <x-forms.post :action="route('admin.form.field.store', ['form' => $form, 'type' => $type])">
        <x-backend.card>
            <x-slot name="header">
                @lang('Create Field')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.form')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                <x-forms.text-input name="label" required="1" label="Label" placeholder="Full Name"/>
                <x-forms.text-input name="placeholder" required="0" label="Placeholder" placeholder="Full Name"/>
                <x-forms.text-input name="class" required="0" label="Additional Class" placeholder="mb-4"/>
                <div class="row">
                    <div class="col-md-6">
                        <x-forms.text-input name="options[rows]" type="number" required="0" label="Rows" value="10" placeholder="Any number for rows"/>
                    </div>
                    <div class="col-md-6">
                        <x-forms.text-input name="options[cols]" type="number" required="0" label="Cols" value="10" placeholder="Any number for cols"/>
                    </div>
                </div>
                <div class="d-flex flex-stack mb-8">
                    <div class="me-5">
                        <label class="fs-6 fw-semibold">Required</label>

                        <div class="fs-7 fw-semibold text-muted">This field will be required</div>
                    </div>
                    <label class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name="is_required">
                    </label>
                </div>
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Create')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection
