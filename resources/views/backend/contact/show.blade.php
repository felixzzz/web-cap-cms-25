@extends('backend.layouts.app')

@section('title', __('View Detail Submission'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('View Detail Submission')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.contact.index')" :text="__('Back')" />
        </x-slot>

        <x-slot name="body">
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>@lang('Subject')</th>
                        <td>
                            {{ $contact->topic?->name ?? 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <th>@lang('Name')</th>
                        <td>{{ $contact->firstname }} {{ $contact->lastname }}</td>
                    </tr>
                    <tr>
                        <th>@lang('Email')</th>
                        <td>{{ $contact->email }}</td>
                    </tr>
                    <tr>
                        <th>@lang('Message')</th>
                        <td>{{ $contact->message ?: '-' }}</td>
                    </tr>

                    {{-- Additional Information --}}
                    @if ($contact->country)
                    <tr>
                        <th>@lang('Country')</th>
                        <td>{{ $contact->country }}</td>
                    </tr>
                    @endif

                    @if ($contact->topic_id)
                    <tr>
                        <th>@lang('Topic')</th>
                        <td>{{ $contact->topic?->name ?? 'N/A' }}</td>
                    </tr>
                    @endif

                    @if ($contact->created_at)
                    <tr>
                        <th>@lang('Created At')</th>
                        <td>@displayDate($contact->created_at) ({{ $contact->created_at->diffForHumans() }})</td>
                    </tr>
                    @endif

                    @if ($contact->updated_at)
                    <tr>
                        <th>@lang('Updated At')</th>
                        <td>@displayDate($contact->updated_at) ({{ $contact->updated_at->diffForHumans() }})</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </x-slot>

        <x-slot name="footer">
            <small class="float-end text-muted">
                <strong>@lang('Created at'):</strong>
                @displayDate($contact->created_at)
                ({{ $contact->created_at }})
            </small>
        </x-slot>
    </x-backend.card>
@endsection
