<div>
    <div class="modal fade" id="kt_modal_banner_active_list" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered mw-800px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Embedded Posts: <span
                            class="badge badge-light-primary fs-4">{{ $bannerGroupTitle }}</span></h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                    fill="currentColor" />
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <div class="table-responsive">
                        <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="min-w-200px">Post Title</th>
                                    <th class="min-w-100px">Language</th>
                                    <th class="min-w-100px">Position</th>
                                    <th class="min-w-150px">Schedule</th>
                                    <th class="min-w-100px text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeBanners as $active)
                                    <tr>
                                        <td>
                                            <div class="text-dark fw-bold fs-6">
                                                {{ $active->post ? ($active->language == 'en' ? $active->post->title_en : $active->post->title) : 'Unknown Post' }}
                                            </div>
                                            <span
                                                class="text-muted fw-semibold text-muted d-block fs-7">{{ $active->post ? $active->post->type : '-' }}</span>
                                        </td>
                                        <td>
                                            @if($active->language == 'en')
                                                <span class="badge badge-light-danger">EN</span>
                                            @else
                                                <span class="badge badge-light-primary">ID</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="badge badge-light-info text-capitalize">{{ $active->location }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 fs-7">Start:
                                                    {{ $active->start_date ? $active->start_date->format('d M Y H:i') : '-' }}</span>
                                                <span class="text-gray-800 fs-7">End:
                                                    {{ $active->end_date ? $active->end_date->format('d M Y H:i') : '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <button type="button"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm"
                                                data-bs-toggle="tooltip" title="Delete" onclick="Swal.fire({
                                                            title: 'Are you sure want remove this banner from active list?',
                                                            text: 'You wont be able to revert this!',
                                                            icon: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '#3085d6',
                                                            cancelButtonColor: '#d33',
                                                            confirmButtonText: 'Yes, delete it!'
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                @this.call('delete', {{ $active->id }});
                                                            }
                                                        })">
                                                <span class="svg-icon svg-icon-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z"
                                                            fill="currentColor" />
                                                        <path opacity="0.5"
                                                            d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z"
                                                            fill="currentColor" />
                                                        <path opacity="0.5"
                                                            d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z"
                                                            fill="currentColor" />
                                                    </svg>
                                                </span>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No embedded posts found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('open-banner-active-list-modal', event => {
            $('#kt_modal_banner_active_list').modal('show');
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        });
    </script>
</div>