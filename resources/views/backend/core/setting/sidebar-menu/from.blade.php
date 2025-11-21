@extends('backend.layouts.app')

@section('title', __('View User'))

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ !$sidebarMenu->id ? route('admin.general.sidebar-menu.store') : route('admin.general.sidebar-menu.update',$sidebarMenu) }}" method="POST">
                    @method($sidebarMenu->id ? 'PUT' : 'POST')
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{$sidebarMenu->id ? 'Edit' : 'Add'}} Sidebar Item</h3>
                        </div>
                        @If ($sidebarMenu?->children->count() > 0))
                        <div class="card-body">
                            <h5 class="card-title">Sidebar</h5>
                            <ul id="sortable-sidebar" class="list-group connectedSortable">
                                @foreach($sidebarMenu->children as $item)
                                    <li class="list-group-item {{$item->is_active ? '' : 'text-muted'}}" data-id="{{ $item->id }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                {{ $item->title }}
                                            </div>
                                            <div>
                                                <a href="{{route('admin.general.sidebar-menu.edit',$item)}}" class="btn btn-sm btn-primary mr-1">Edit</a>
                                                <a href="#" class="btn btn-sm btn-danger">Delete</a>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <div class="card-body">
                            @csrf
                            <div class="row mb-8">
                                <label for="title" class="col-md-3 form-label">Title</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="title" name="title"
                                           value="{{ old('title',$sidebarMenu->title)}}"
                                           required>
                                </div>
                            </div>
                            <div class="row mb-8">
                                <label for="parent_id" class="col-md-3 form-label">Parent Item</label>
                                <div class="col-md-9">
                                    <select class="form-control" id="parent_id" name="parent_id" {{$sidebarMenu?->children->count() > 0 ? 'disabled' : ''}}>
                                        <option value="">Select Parent Item</option>
                                        @foreach($sidebarItems as $item)
                                            <option value="{{ $item->id }}"
                                                    {{ old('parent_id',$sidebarMenu->parent_id) == $item->id ? 'selected' : '' }}
                                            >{{ $item->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-8">
                                <label for="url" class="col-md-3 form-label">URL</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="url" name="url"
                                          value="{{ old('url',$sidebarMenu->url)}}">
                                </div>
                            </div>
                            <div class="row mb-8">
                                <label for="icon" class="col-md-3 form-label">Icon</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="icon" name="icon"
                                             value="{{ old('icon',$sidebarMenu->icon)}}">
                                </div>
                            </div>
                            <div class="row mb-8">
                                <label for="icon" class="col-md-3 form-label">Permission</label>
                                <div class="col-md-9">
                                    <select class="form-control" id="permissions" name="permissions">
                                        <option value="">No Permission</option>
                                        @foreach($permissions as $item)
                                            <option value="{{$item->id}}"
                                                    {{ old('permission',$sidebarMenu?->permissions?->first()?->id) === $item->id  ? 'selected' : '' }}
                                            >{{ $item->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-8">
                                <label class="col-md-3 form-label">Is Active?</label>
                                <div class="col-md-9">
                                    <select class="form-control" id="is_active" name="is_active">
                                        <option value="1" {{old('is_active', $sidebarMenu->is_active) ? 'selected' : ''}}>Yes</option>
                                        <option value="0" {{!old('is_active', $sidebarMenu->is_active) ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{route('admin.general.sidebar-menu.index')}}" class="btn btn-sm btn-warning float-start">Back</a>
                            <button type="submit" class="btn btn-sm btn-primary float-end">Add Sidebar Item</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(function () {
            $("#sortable-sidebar").sortable({
                update: function (event, ui) {
                    var sidebarItems = [];

                    $("#sortable-sidebar li").each(function () {
                        sidebarItems.push($(this).data("id"));
                    });

                    $.ajax({
                        url: "{{ route('admin.general.sidebar-menu.update-order') }}",
                        method: "PUT",
                        data: {
                            sidebarItems: sidebarItems,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function (data) {
                            console.log(data);
                        },
                    });
                },
            }).disableSelection();
        });
    </script>
@endpush
