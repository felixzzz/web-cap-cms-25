<div>
    <div class="modal fade" id="kt_modal_banner_embed" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content mh-650px overflow-scroll">
                <div class="modal-header">
                    <h2 class="fw-bold">Embed Banner Group: <span
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

                <div class="modal-body scroll-y mx-xl-8 mb-3">
                    <form wire:submit.prevent="save">
                        <div class="mb-5">
                            <label class="form-label fs-5 fw-bold mb-3">Position:</label>
                            <select class="form-select form-select-solid" wire:model="location">
                                <option value="center">Center</option>
                                <option value="left">Left</option>
                                <option value="right">Right</option>
                                <option value="bottom">Bottom</option>
                            </select>
                            @error('location') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label fs-5 fw-bold mb-3">Start Date:</label>
                                <input type="datetime-local" class="form-control form-control-solid"
                                    wire:model="startDate">
                                @error('startDate') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-5 fw-bold mb-3">End Date:</label>
                                <input type="datetime-local" class="form-control form-control-solid"
                                    wire:model="endDate">
                                @error('endDate') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Separator -->
                        <div class="separator my-5"></div>

                        <div class="mb-3">
                            <label class="form-label fs-4 fw-">Select Posts to embedd: </label>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fs-5 fw-bold mb-3">Filter by Language:</label>
                                <div class="d-flex flex-wrap">
                                    <div class="form-check form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="radio" value="all" id="lang_all"
                                            wire:model="language" />
                                        <label class="form-check-label" for="lang_all">All</label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="radio" value="id" id="lang_id"
                                            wire:model="language" />
                                        <label class="form-check-label" for="lang_id">ID</label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="radio" value="en" id="lang_en"
                                            wire:model="language" />
                                        <label class="form-check-label" for="lang_en">EN</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-5 fw-bold mb-3">Filter by Type:</label>
                                <div class="d-flex flex-wrap">
                                    <div class="form-check form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="radio" value="all" id="type_all"
                                            wire:model="postType" />
                                        <label class="form-check-label" for="type_all">All</label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="radio" value="news" id="type_news"
                                            wire:model="postType" />
                                        <label class="form-check-label" for="type_news">News</label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="radio" value="blog" id="type_blog"
                                            wire:model="postType" />
                                        <label class="form-check-label" for="type_blog">Blog</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="mb-5">
                                        <label class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox"
                                                wire:model="isAllSelected" />
                                            <span class="form-check-label fw-bold text-gray-800">
                                                Select All
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Search by title..." wire:model.debounce.500ms="search">
                                </div>
                            </div>
                            <div class="mh-300px scroll-y p-3 p-lg-5 bg-light rounded">
                                @foreach($posts as $post)
                                    <div class="d-flex align-items-center mb-5">
                                        <label
                                            class="form-check form-check-custom w-100 form-check-solid me-3 border-bottom bg-white p-2">
                                            <input class="form-check-input" type="checkbox" value="{{ $post['id'] }}"
                                                wire:model="selectedPosts" />
                                            <span class="form-check-label fw-bold text-gray-800">
                                                <div class="mb-1">
                                                    @if($post['lang'] == 'en')
                                                        <span class="badge badge-light-danger me-2">EN</span>
                                                    @else
                                                        <span class="badge badge-light-primary me-2">ID</span>
                                                    @endif
                                                    {{ $post['title'] }}
                                                </div>
                                                <span class="text-muted fw-semibold d-block fs-7 mt-1">
                                                    <span class="text-capitalize">{{ $post['type'] }}</span> -
                                                    {{ \Carbon\Carbon::parse($post['created_at'])->format('d M Y H:i') }}
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('selectedPosts') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="text-center">
                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Discard</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress" wire:loading wire:target="save">
                                    Please wait... <span
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
        window.addEventListener('open-banner-embed-modal', event => {
            $('#kt_modal_banner_embed').modal('show');
        });

        window.addEventListener('close-banner-embed-modal', event => {
            $('#kt_modal_banner_embed').modal('hide');
        });
    </script>
</div>