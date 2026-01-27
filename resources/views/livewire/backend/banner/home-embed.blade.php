<div>
    <div class="modal fade" id="kt_modal_banner_home_embed" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content mh-650px overflow-scroll">
                <div class="modal-header">
                    <h2 class="fw-bold">
                        Embed Homepage Banner: <span
                            class="badge badge-light-primary fs-4">{{ $bannerGroupTitle }}</span>
                    </h2>
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

                <div class="modal-body scroll-y mx-xl-8 mb-3">
                    <form wire:submit.prevent="save">
                        <div class="mb-5">
                            <label class="form-label fs-5 fw-bold mb-3">Homepage Section Position:</label>
                            <select class="form-select form-select-solid" wire:model="location">
                                @foreach ($homepageSlots as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('location')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label class="form-label fs-5 fw-bold mb-3">Language:</label>
                            <div class="d-flex flex-wrap">
                                <div class="form-check form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="radio" value="id" id="home_lang_id"
                                        wire:model="language" />
                                    <label class="form-check-label" for="home_lang_id">ID</label>
                                </div>
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="radio" value="en" id="home_lang_en"
                                        wire:model="language" />
                                    <label class="form-check-label" for="home_lang_en">EN</label>
                                </div>
                            </div>
                            @error('language')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label fs-5 fw-bold mb-3">Start Date:</label>
                                <input type="datetime-local" class="form-control form-control-solid"
                                    wire:model="startDate">
                                @error('startDate')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-5 fw-bold mb-3">End Date:</label>
                                <input type="datetime-local" class="form-control form-control-solid"
                                    wire:model="endDate">
                                @error('endDate')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
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
        window.addEventListener('open-banner-home-embed-modal', event => {
            $('#kt_modal_banner_home_embed').modal('show');
        });

        window.addEventListener('close-banner-home-embed-modal', event => {
            $('#kt_modal_banner_home_embed').modal('hide');
        });

        // Reuse swal-error if it's global, but better to be safe
        window.addEventListener('swal-error', event => {
            Swal.fire({
                icon: 'error',
                title: event.detail.title,
                text: event.detail.text,
            });
        });

        window.addEventListener('confirm-homepage-replace', event => {
            Swal.fire({
                title: 'Slot already taken!',
                text: "A banner is already active in this slot (" + event.detail.location +
                    "). Do you want to replace it?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, replace it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('forceSaveHomepage');
                }
            })
        });
    </script>
</div>
