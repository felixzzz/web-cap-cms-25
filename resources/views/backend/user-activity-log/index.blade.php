@extends('layouts.base')
@section('title', 'User Activity Log')
@section('page')
    <div class="card card-xl-stretch mb-xl-8">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1">User Activity Log</span>
            </h3>
        </div>
        <div class="card-body py-3">
            <div class="d-flex flex-column">
                <form action="#" method="get">
                    <div class="d-md-flex justify-content-between mb-3">
                        <div class="d-md-flex">
                            <div class="mb-3 mb-md-0 input-group">
                                <input name="search" value="{{request()->query('search')}}" placeholder="Search Name or Email" type="text" class="form-control">
                            </div>
                            <div class="px-4 mb-3 mb-md-0">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            {{--            <livewire:backend.user-activity-log.user-activity-log-table />--}}
            <div class="table-responsive table align-middle gs-0 gy-4">
                <table class="table table-hober">
                    <thead class="fw-bold ">
                    <tr>
                        <th class="px-4">Activity</th>
                        <th class="px-4">Performed At</th>
                        <th class="px-4 text-end">Action</th>
                    </thead>
                    <tbody class="px-4 py-8">
                    @forelse($data as $item)
                        <tr class="border-top">
                            <td class="px-4">
                                {{ $item->formatted }}
                            </td>
                            <td class="px-4">
                                {{ $item->created_at }}
                            </td>
                            <td class="text-end">
                                <x-utils.view-button :href="route('admin.user-activity-log.show', $item->id)"/>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">There is no category exists</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $query->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
    </script>
@endpush
