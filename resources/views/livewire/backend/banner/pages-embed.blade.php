<div>
    <div class="modal fade" id="bannerPagesEmbedModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content mh-650px overflow-scroll">
                <div class="modal-header">
                    <h2 class="fw-bold">
                        @lang('Embed Banner to Pages'): <span
                            class="badge badge-light-primary fs-4">{{ $bannerGroupTitle }}</span>
                    </h2>
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
                <div class="modal-body scroll-y mx-xl-8 mb-3">
                    <form wire:submit.prevent="save">
                        <div class="mb-5">
                            <label class="form-label fs-5 fw-bold mb-3">@lang('Position'):</label>
                            <select class="form-select form-select-solid" wire:model="location">
                                @foreach ($locations as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('location')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                            @if (in_array($location, ['left', 'right']))
                                <div class="form-check form-check-custom form-check-solid mt-4">
                                    <input class="form-check-input" type="checkbox" wire:model="isHideInMobile"
                                        id="hide_in_mobile_pages" />
                                    <label class="form-check-label" for="hide_in_mobile_pages">
                                        @lang('Hide in Mobile')
                                    </label>
                                </div>
                            @endif
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label fs-5 fw-bold mb-3">@lang('Start Date'):</label>
                                <input type="datetime-local" class="form-control form-control-solid"
                                    wire:model="startDate">
                                @error('startDate')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-5 fw-bold mb-3">@lang('End Date'):</label>
                                <input type="datetime-local" class="form-control form-control-solid"
                                    wire:model="endDate">
                                @error('endDate')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="separator my-5"></div>

                        <div class="mb-3">
                            <label class="form-label fs-4 fw-">@lang('Select Pages to embed'): </label>
                            <span class="text-muted fs-7">({{ $totalPostsCount }} @lang('total pages')</span>)
                        </div>

                        <div class="mb-5">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <div class="d-flex align-items-center">
                                        <label class="form-check form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox"
                                                wire:model="isAllSelected" 
                                                id="selectAllPagesCheckbox" />
                                            <span class="form-check-label fw-bold text-gray-800">
                                                @lang('Select All')
                                            </span>
                                        </label>
                                        @if (count($selectedPosts) > 0)
                                            <span class="badge badge-primary">{{ count($selectedPosts) }} / {{ $totalPostsCount }}
                                                @lang('selected')</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <select class="form-select form-select-solid w-80px me-2" wire:model="perPage" data-control="select2" data-hide-search="true" data-placeholder="Per page" id="pagesPerPageSelect">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <select class="form-select form-select-solid w-80px me-2" wire:model="language">
                                        <option value="all">All</option>
                                        <option value="id">ID</option>
                                        <option value="en">EN</option>
                                    </select>
                                    <input type="text" class="form-control form-control-solid w-200px" placeholder="Search..."
                                        wire:model.debounce.500ms="search">
                                </div>
                            </div>
                            
                            <div class="mh-300px scroll-y p-3 p-lg-5 bg-light rounded">
                                @forelse($posts as $post)
                                    <div class="d-flex align-items-center mb-3">
                                        <label
                                            class="form-check form-check-custom w-100 form-check-solid me-3 border-bottom bg-white p-2 rounded shadow-sm">
                                            <input class="form-check-input page-checkbox" type="checkbox" value="{{ $post['id'] }}"
                                                wire:model="selectedPosts" />
                                            <span class="form-check-label fw-bold text-gray-800">
                                                <div class="mb-1">
                                                    @if ($post['lang'] == 'en')
                                                        <span class="badge badge-light-danger me-2">EN</span>
                                                    @else
                                                        <span class="badge badge-light-primary me-2">ID</span>
                                                    @endif
                                                    {{ $post['title'] }}
                                                    <span class="text-muted fs-7 ms-2">({{ $post['slug'] }})</span>
                                                    @if (isset($post['status']) && $post['status'] !== 'publish')
                                                        <span
                                                            class="badge badge-light-warning ms-2 text-capitalize">{{ $post['status'] }}</span>
                                                    @endif
                                                </div>
                                            </span>
                                        </label>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-5">@lang('No pages found')</div>
                                @endforelse
                            </div>

                            {{-- Pagination Controls --}}
                            @php
                                $totalPages = ceil($totalPostsCount / $perPage);
                                $currentPage = $page;
                            @endphp
                            @if ($totalPages > 1)
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="text-muted fs-7">
                                        @lang('Showing') {{ count($posts) }} @lang('of') {{ $totalPostsCount }} @lang('pages')
                                        (@lang('Page') {{ $currentPage }} @lang('of') {{ $totalPages }})
                                    </div>
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination pagination-sm mb-0">
                                            {{-- Previous Button --}}
                                            <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                                                <a class="page-link" href="#" wire:click.prevent="gotoPage({{ $currentPage - 1 }})" 
                                                   @if($currentPage <= 1) tabindex="-1" aria-disabled="true" @endif>
                                                    <i class="bi bi-chevron-left"></i>
                                                </a>
                                            </li>
                                            
                                            {{-- Page Numbers --}}
                                            @php
                                                $startPage = max(1, $currentPage - 2);
                                                $endPage = min($totalPages, $currentPage + 2);
                                            @endphp
                                            
                                            @if ($startPage > 1)
                                                <li class="page-item">
                                                    <a class="page-link" href="#" wire:click.prevent="gotoPage(1)">1</a>
                                                </li>
                                                @if ($startPage > 2)
                                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                                @endif
                                            @endif
                                            
                                            @for ($i = $startPage; $i <= $endPage; $i++)
                                                <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                                    <a class="page-link" href="#" wire:click.prevent="gotoPage({{ $i }})">{{ $i }}</a>
                                                </li>
                                            @endfor
                                            
                                            @if ($endPage < $totalPages)
                                                @if ($endPage < $totalPages - 1)
                                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                                @endif
                                                <li class="page-item">
                                                    <a class="page-link" href="#" wire:click.prevent="gotoPage({{ $totalPages }})">{{ $totalPages }}</a>
                                                </li>
                                            @endif
                                            
                                            {{-- Next Button --}}
                                            <li class="page-item {{ $currentPage >= $totalPages ? 'disabled' : '' }}">
                                                <a class="page-link" href="#" wire:click.prevent="gotoPage({{ $currentPage + 1 }})"
                                                   @if($currentPage >= $totalPages) tabindex="-1" aria-disabled="true" @endif>
                                                    <i class="bi bi-chevron-right"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            @endif
                        </div>

                        <div class="text-center">
                            <button type="reset" class="btn btn-light me-3"
                                data-bs-dismiss="modal">@lang('Discard')</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">@lang('Submit')</span>
                                <span class="indicator-progress" wire:loading wire:target="save">
                                    @lang('Please wait...') <span
                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('open-banner-pages-embed-modal', event => {
            $('#bannerPagesEmbedModal').modal('show');
        });

        window.addEventListener('close-banner-pages-embed-modal', event => {
            $('#bannerPagesEmbedModal').modal('hide');
        });

        window.addEventListener('swal:confirm-overlap', event => {
            let details = event.detail.details;
            let nonConflictingPosts = event.detail.nonConflictingPosts || [];
            console.log('Conflict Details received:', details);

            let detailsHtml = '<div class="text-start" style="text-align: left; max-height: 300px; overflow-y: auto;">';
            detailsHtml += '<p class="mb-2"><strong>The following pages have conflicts. Check the ones you want to add anyway:</strong></p>';

            if (Array.isArray(details)) {
                details.forEach((detail, index) => {
                    const id = detail.id || index;
                    const label = detail.label || detail;
                    detailsHtml += '<div class="form-check mb-2">';
                    detailsHtml += '<input class="form-check-input conflict-checkbox" type="checkbox" value="' + id + '" id="conflict_' + index + '" checked>';
                    detailsHtml += '<label class="form-check-label" for="conflict_' + index + '">' + label + '</label>';
                    detailsHtml += '</div>';
                });
            }
            detailsHtml += '</div>';

            Swal.fire({
                title: 'Conflict Detected!',
                html: detailsHtml,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm',
                width: '600px'
            }).then((result) => {
                if (result.isConfirmed) {
                    const selectedConflictPosts = [];
                    document.querySelectorAll('.conflict-checkbox:checked').forEach(checkbox => {
                        selectedConflictPosts.push(checkbox.value);
                    });
                    @this.call('forceSave', selectedConflictPosts);
                }
            })
        });
    </script>
</div>