@extends('backend.layouts.app')

@section('title', __('View User'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('View User Activity Log')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link icon="fa fa-left" class="btn btn-sm btn-outline" :href="route('admin.user-activity-log.index')" :text="__('Back')" />
            &nbsp
        </x-slot>

        <x-slot name="body">
            <table class="table table-hover">
                <tr>
                    <th>@lang('User')</th>
                    <td>{{ $activity->causer?->name }} - {{ $activity->causer?->email }}</td>
                </tr>
                <tr>
                    <th>@lang('Description')</th>
                    <td>{{ $activity->description }}</td>
                </tr>
                <tr>
                    <th>@lang('Subject')</th>
                    <td>{{ $activity->subject_type }}</td>
                </tr>
                <tr>
                    <th>@lang('Changes')</th>
                    <td>
                        <pre class="json">{{ $activity->changes }}</pre>
                    </td>
                </tr>
            </table>

            <table class="table table-hover">
                <thead>
                    <th>Attribute</th>
                    <th>From</th>
                    <th>To</th>
                </thead>
                <tbody>

                </tbody>
                <tr>
                    <th>@lang('User')</th>
                    <td>{{ $activity->causer?->name }} - {{ $activity->causer?->email }}</td>
                </tr>
                <tr>
                    <th>@lang('Description')</th>
                    <td>{{ $activity->description }}</td>
                </tr>
                <tr>
                    <th>@lang('Subject')</th>
                    <td>{{ $activity->subject_type }}</td>
                </tr>
                <tr>
                    <th>@lang('Changes')</th>
                    <td>
                        <pre class="json">{{ $activity->changes }}</pre>
                    </td>
                </tr>
            </table>
        </x-slot>

        <x-slot name="footer">
            <small class="float-right text-muted">
                <strong>@lang('Log Time'):</strong> @displayDate($activity->created_at)
                ({{ $activity->created_at->diffForHumans() }}),
            </small>
        </x-slot>
    </x-backend.card>
@endsection
@push('scripts')
    <script>
        $(formatJson);

        function formatJson() {
            var element = $(".json");
            element.each(function(i, e) {
                var txt = $(e).text();
                if (txt) {
                    try {
                        var obj = JSON.parse(txt);
                        console.log(obj);
                        $(e).html(JSON.stringify(obj, undefined, 2));
                    } catch (e) {
                        console.log(txt, "not a valid JSON");
                    }
                }
            })
        }
    </script>
@endpush
