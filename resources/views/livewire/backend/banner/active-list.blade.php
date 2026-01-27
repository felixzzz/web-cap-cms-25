<div>
    <div class="modal fade" id="kt_modal_banner_active_list" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered mw-1000px">
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
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="currentColor" />
                            </svg>
                        </span>
                    </div>
                </div>




                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    @if ($isEditing)
                        <div class="mb-5">
                            <h3 class="mb-4">Edit Active Banner</h3>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label required">Position</label>
                                    <select class="form-select" wire:model.defer="editingLocation">
                                        <option value="">Select Position</option>
                                        @if ($position == 'home')
                                            <option value="journey-growth">Journey Growth</option>
                                            <option value="financial-report">Financial Report</option>
                                        @else
                                            <option value="center">Center</option>
                                            <option value="left">Left</option>
                                            <option value="right">Right</option>
                                            <option value="bottom">Bottom</option>
                                        @endif
                                    </select>
                                    @error('editingLocation')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Language</label>
                                    <select class="form-select" wire:model.defer="editingLanguage">
                                        <option value="id">ID</option>
                                        <option value="en">EN</option>
                                    </select>
                                    @error('editingLanguage')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Start Date</label>
                                    <input type="datetime-local" class="form-control"
                                        wire:model.defer="editingStartDate">
                                    @error('editingStartDate')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">End Date</label>
                                    <input type="datetime-local" class="form-control" wire:model.defer="editingEndDate">
                                    @error('editingEndDate')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="button" class="btn btn-secondary" wire:click="cancelEdit">Cancel</button>
                                <button type="button" class="btn btn-primary" wire:click="update">Save Changes</button>
                            </div>
                        </div>
                    @else
                        @if (count($selected) > 0)
                            <div class="d-flex justify-content-end mb-4">
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDeleteSelected()">
                                    Delete Selected ({{ count($selected) }})
                                </button>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th class="w-25px">
                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input" type="checkbox"
                                                    wire:model="selectAll" />
                                            </div>
                                        </th>
                                        <th class="min-w-200px">Post Title</th>
                                        <th class="min-w-100px">Language</th>
                                        <th class="min-w-100px">Position</th>
                                        <th class="min-w-150px">Schedule</th>
                                        <th class="min-w-100px">Status</th>
                                        <th class="min-w-100px text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($activeBanners as $active)
                                        @php
                                            $now = now();
                                            $status = 'Active';
                                            $badgeClass = 'badge-light-success';

                                            if ($active->start_date && $active->start_date > $now) {
                                                $status = 'Scheduled';
                                                $badgeClass = 'badge-light-warning';
                                            } elseif ($active->end_date && $active->end_date < $now) {
                                                $status = 'Expired';
                                                $badgeClass = 'badge-light-danger';
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="{{ $active->id }}" wire:model="selected" />
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-dark fw-bold fs-6">
                                                    {{ $active->post ? ($active->language == 'en' ? $active->post->title_en : $active->post->title) : 'Unknown Post' }}
                                                </div>
                                                <span
                                                    class="text-muted fw-semibold text-muted d-block fs-7">{{ $active->post ? $active->post->type : '-' }}</span>
                                            </td>
                                            <td>
                                                @if ($active->language == 'en')
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
                                            <td>
                                                <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                                            </td>
                                            <td class="text-end">
                                                <button
                                                    class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                                                    wire:click="edit({{ $active->id }})" title="Edit">
                                                    <span class="svg-icon svg-icon-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none">
                                                            <path opacity="0.3"
                                                                d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59597C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59597L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                                                fill="currentColor" />
                                                            <path
                                                                d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                                                fill="currentColor" />
                                                        </svg>
                                                    </span>
                                                </button>
                                                <button
                                                    class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm"
                                                    onclick="confirmDelete({{ $active->id }})" title="Delete">
                                                    <span class="svg-icon svg-icon-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none">
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
                                            <td colspan="7" class="text-center text-muted">No embedded posts found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('open-banner-active-list-modal', event => {
            $('#kt_modal_banner_active_list').modal('show');
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(
                tooltipTriggerEl))
        });

        function confirmDeleteSelected() {
            Swal.fire({
                title: 'Are you sure want remove selected banners?',
                text: 'You wont be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete them!'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteSelected');
                }
            })
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure want remove this banner?',
                text: 'You wont be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('delete', id);
                }
            })
        }
    </script>
</div>
