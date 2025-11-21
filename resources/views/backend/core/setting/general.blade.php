@inject('model', '\App\Models\Option')

@extends('backend.layouts.app')

@section('title', 'General Setting')

@section('content')
    <x-forms.post id="general-form" :action="route('admin.general.store')" class="card mb-5 mb-xxl-8">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title ">
                General Setting
            </h3>
        </div>
        <div class="card-body">
            <x-forms.text-input name="app[name]" label="Site Title" value="{{ $model->getOption('app.name') }}" isSide="1" text="The site title" required="1" />
            <x-forms.text-input name="app[tagline]" label="Tagline" value="{{ $model->getOption('app.tagline') }}" isSide="1" text="In a few words, explain what this site is about." required="1"/>
            <x-forms.text-input name="app[mail][admin]" label="Administration Email Address" value="{{ $model->getOption('app.mail.admin') }}" isSide="1" required="1" text="This address is used for admin purposes." type="email"/>
            <x-forms.checkbox.single-checkbox name="app[register][user]" label="User Registration" checked="{{ ($model->getOption('app.register.user') != null) ? $model->getOption('app.register.user') : '0'}}" text="User can register"/>
            <x-forms.checkbox.single-checkbox name="app[register][admin]" label="Admin Registration" checked="{{ ($model->getOption('app.register.admin') != null) ? $model->getOption('app.register.admin') : '0'}}" text="Admin can register"/>

            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1">SEO</span>
            </h3>
            <x-forms.text-input name="app[meta_title]" label="Meta Title" value="{{ $model->getOption('app.meta_title') }}" isSide="1" text="The Meta title" required="1"/>
            <x-forms.text-input name="app[meta_description]" label="Meta Description" value="{{ $model->getOption('app.meta_description') }}" isSide="1" text="The Meta description" required="0"/>
            <x-forms.text-input name="app[meta_keywords]" label="Meta Keywords" value="{{ $model->getOption('app.meta_keywords') }}" isSide="1" text="The Meta keywords" required="0"/>
        </div>
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button id="edit-button" type="button" class="btn btn-primary">Edit</button>
            <button id="save-button" type="submit" class="btn btn-primary" style="display:none">Save Changes</button>
        </div>
    </x-forms.post>
    @push('scripts')
    <script>
        $(document).ready(function() {
            // Set initial disabled attribute value
            $('#general-form input[name^="app"]').prop('disabled', true);
            $('#general-form input[type="checkbox"]').prop('disabled', true);

            // Enable form fields on edit button click
            $('#edit-button').click(function() {
                $('#general-form input[name^="app"]').prop('disabled', function(_, value) {
                    return value ? false : 'disabled';
                });
                $('#general-form input[type="checkbox"]').prop('disabled', false);
                $('#edit-button').hide();
                $('#save-button').show();
            });
        });
    </script>
@endpush

@endsection


