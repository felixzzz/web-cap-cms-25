@inject('model', '\App\Models\Option')

@extends('backend.layouts.app')

@section('title', 'SMTP')

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('SMTP Configuration')
        </x-slot>

        <x-slot name="body">
            <table class="table table-hover">
                <tr>
                    <th>Hostname</th>
                    <td>{{ $model->getOption('mail.mailers.smtp.host') }}</td>
                </tr>
                <tr>
                    <th>Port</th>
                    <td>{{ $model->getOption('mail.mailers.smtp.port') }}</td>
                </tr>
                <tr>
                    <th>Encryption</th>
                    <td>{{ $model->getOption('mail.mailers.smtp.encryption') }}</td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td>{{ $model->getOption('mail.mailers.smtp.username') }}</td>
                </tr>
                <tr>
                    <th>Password</th>
                    <td>{{ $model->getOption('mail.mailers.smtp.password') }}</td>
                </tr>
                <tr>
                    <th>From Address</th>
                    <td>{{ $model->getOption('mail.from.address') }}</td>
                </tr>
                <tr>
                    <th>From Address</th>
                    <td>{{ $model->getOption('mail.from.name') }}</td>
                </tr>

            </table>
            <div class="card-footer d-flex justify-content-end py-0 pt-6 px-9">
                <a href="{{ route('admin.smtp.edit') }}" class="btn btn-primary me-2">Edit Configuration</a>
                <a href="{{ route('admin.smtp.test') }}" class="btn btn-success">Send Testing</a>
            </div>
        </x-slot>

    </x-backend.card>
@endsection
