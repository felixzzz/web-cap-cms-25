@extends('layouts.base')
@section('title', 'Sidebar Menu')
@section('page')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            Sidebar Menu
                        </h3>
                        <div class="card-toolbar">
                            <x-utils.link icon="fa fa-plus" class="btn btn-sm btn-primary" :href="route('admin.general.sidebar-menu.create')"
                                :text="__('Create Sidebar')" />
                        </div>
                    </div>
                    <div class="card-body">
                        <ul id="sortable-sidebar" class="list-group connectedSortable">
                            @foreach ($sidebarItems as $item)
                                <li class="list-group-item {{ $item->is_active ? '' : 'text-muted' }}"
                                    data-id="{{ $item->id }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            {{ $item->title }}
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.general.sidebar-menu.edit', $item) }}"
                                                class="btn btn-sm btn-primary mr-1">Edit</a>
                                            <a href="#"
                                                data-target="{{ route('admin.general.sidebar-menu.destroy', $item) }}"
                                                class="btn btn-sm btn-danger btn-delete-item">Delete</a>
                                        </div>
                                    </div>
                                    @if (count($item->children) > 0)
                                        <ul class="list-group mt-2">
                                            @foreach ($item->children as $child)
                                                <li class="list-group-item {{ $child->is_active ? '' : 'text-muted' }}"
                                                    data-id="{{ $child->id }}">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            {{ $child->title }}
                                                        </div>
                                                        <div>
                                                            <a href="{{ route('admin.general.sidebar-menu.edit', $child) }}"
                                                                class="btn btn-sm btn-primary mr-1">Edit</a>
                                                            <a href="#"
                                                                data-target="{{ route('admin.general.sidebar-menu.destroy', $child) }}"
                                                                class="btn btn-sm btn-danger btn-delete-item">Delete</a>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(function() {
            $("#sortable-sidebar").sortable({
                update: function(event, ui) {
                    var sidebarItems = [];

                    $("#sortable-sidebar li").each(function() {
                        sidebarItems.push($(this).data("id"));
                    });

                    $.ajax({
                        url: "{{ route('admin.general.sidebar-menu.update-order') }}",
                        method: "PUT",
                        data: {
                            sidebarItems: sidebarItems,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            console.log(data);
                        },
                    });
                },
            }).disableSelection();
        });
        /*swal for detel sidebar menu*/
        $(document).on('click', '.btn-delete-item', function(e) {
            e.preventDefault();
            var $this = $(this);
            var url = $this.data('target');
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this sidebar menu!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        method: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            Swal.fire(
                                'Deleted!',
                                'Your Sidebar Menu has been deleted.',
                                'success'
                            )
                            $this.closest('li').remove();
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    /*Swal.fire(
                        'Cancelled',
                        'Your Sidebar Menu is safe :)',
                        'error'
                    )*/
                }
            })
        });
    </script>
@endpush
