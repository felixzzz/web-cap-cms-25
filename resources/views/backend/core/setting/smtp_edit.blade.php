@inject('model', '\App\Models\Option')

@extends('backend.layouts.app')

@section('title', 'SMTP')

@section('content')
        <x-forms.post :action="route('admin.smtp.store')" class="card mb-5 mb-xxl-8">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">SMTP Configuration</span>
                </h3>
            </div>
            <div class="card-body">
                <x-forms.text-input name="mail[mailers][smtp][host]" label="Hostname" placeholder="smtp.server.com" value="{{ $model->getOption('mail.mailers.smtp.host') }}" isSide="1" text="Specify the hostname or IP address of your SMTP mail server. Eg. smtp.yourcompany.com" required="1"/>
                <x-forms.text-input name="mail[mailers][smtp][port]" type="number" placeholder="456" label="Port" value="{{ $model->getOption('mail.mailers.smtp.port') }}" isSide="1" text="The SMTP port number, usually 25 for SMTP or 465 for SMTPS, either of which are assumed if this field is left blank." required="1"/>
                <x-forms.text-input name="mail[mailers][smtp][encryption]" placeholder="TLS, SSL, STARTTLS" label="Encryption" value="{{ $model->getOption('mail.mailers.smtp.encryption') }}" isSide="1" required="1"/>
                <x-forms.text-input name="mail[mailers][smtp][username]" placeholder="username" label="Username" value="{{ $model->getOption('mail.mailers.smtp.username') }}" isSide="1" text="If your SMTP host requires authentication, specify the username of these authentication credentials here. (Most company servers require authentication to relay mail to non-local users.)"/>
                <x-forms.text-input name="mail[mailers][smtp][password]" placeholder="password" type="password" label="Password" value="{{ $model->getOption('mail.mailers.smtp.password') }}" isSide="1" text="Again, if your SMTP host requires authentication, specify the password associated with the username you specified above."/>
                <x-forms.text-input name="mail[from][address]" placeholder="no-reply@domain.com" type="text" label="From Address" value="{{ $model->getOption('mail.from.address') }}" isSide="1" text="Set the sender email address from e.g no-reply@domain.com"/>
                <x-forms.text-input name="mail[from][name]" placeholder="No-Reply" type="text" label="From Name" value="{{ $model->getOption('mail.from.name') }}" isSide="1" text="Set the sender name from e.g No-Reply {{ $model->getOption('app.name') }}"/>
            </div>
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="submit" class="btn btn-primary me-2">Save Changes</button>
            </div>
        </x-forms.post>
@endsection
