@extends('backend.layouts.app')

@section('title', 'Field of '.$form->name)

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Field of '.$form->name)
        </x-slot>

        @if ($logged_in_user->can('admin.access.form-fields.create'))
            <x-slot name="headerActions">
                <x-forms.modal-button id-modal="create_field" icon="fa fa-plus" title="Create a new field" label="Create new field" description="Please pick the field type first.">
                    <form method="get" action="{{ route('admin.form.field.create', $form) }}" class="mh-475px scroll-y me-n7 pe-7">
                        <x-forms.select label="Field Type" name="type" placeholder="Select an option" required="1" multiple="0" text="" hidden="0" required="1">
                            <option value="input">Input</option>
                            <option value="select">Select</option>
                            <option value="textarea">Textarea</option>
                        </x-forms.select>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">
                                    Continue
                                </span>
                            </button>
                        </div>
                    </form>
                </x-forms.modal-button>
            </x-slot>
        @endif

        <x-slot name="body">
            <livewire:backend.form.field-list form="{{ $form->id }}" status="publish"/>
        </x-slot>
    </x-backend.card>
@endsection
