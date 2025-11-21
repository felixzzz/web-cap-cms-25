<div class="modal fade" id="add_new_post_type" tabindex="-1" aria-modal="true" role="dialog" style="display: none; padding-left: 0px;">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content rounded">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                            </svg>
                        </span>
                </div>
            </div>
            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                <form method="post" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('post.type.custom.store') }}">
                    @csrf
                    <div class="mb-13 text-center">
                        <h1 class="mb-3">Create Custom Post Type</h1>
                        <div class="text-muted fw-semibold fs-5">
                            Custommize every post outside the actual post.
                        </div>
                    </div>
                    <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                            <span class="required">Type Name</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" placeholder="Enter Post Type Name" name="name" required>
                        @error('name')
                            <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row g-9 mb-8">
                        <div class="col-md-12 fv-row fv-plugins-icon-container">
                            <label class="required fs-6 fw-semibold mb-2">Icons</label>
                            <select class="form-select form-select-solid" name="icon" placeholder="..." id="kt_docs_select2_country">
                                @foreach ($icons as $item)
                                    <option value="{{ $item }}" data-kt-select2-icon="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            @error('icon')
                            <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-8 fv-row">
                        <div class="d-flex flex-stack">
                            <div class="fw-semibold me-5">
                                <label class="fs-6">Post Type</label>
                                <div class="fs-7 text-muted">The kind of this type was</div>
                            </div>
                            <div class="d-flex align-items-center">
                                <label class="form-check form-check-custom form-check-solid me-10">
                                    <input class="form-check-input h-20px w-20px" type="radio" name="type" value="post" checked="checked">
                                    <span class="form-check-label fw-semibold">Post</span>
                                </label>
                                <label class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input h-20px w-20px" type="radio" name="type" value="page">
                                    <span class="form-check-label fw-semibold">Page</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-stack mb-8">
                        <div class="me-5">
                            <label class="fs-6 fw-semibold">Public</label>
                            <div class="fs-7 fw-semibold text-muted">Allow other user accessing this post type</div>
                        </div>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" name="is_public" value="1" checked="checked">
                            <span class="form-check-label fw-semibold text-muted">Allowed</span>
                        </label>
                    </div>
                    <div class="d-flex flex-stack mb-15">
                        <div class="me-5">
                            <label class="fs-6 fw-semibold">Show in Menu</label>
                            <div class="fs-7 fw-semibold text-muted">
                                Show this post type in the sidebar menu.
                            </div>
                        </div>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input" name="show_in_menu" type="checkbox" value="1" checked="checked">
                            <span class="form-check-label fw-semibold text-muted">Show</span>
                        </label>
                    </div>
                    <div class="text-center">
                        <button type="reset" id="kt_modal_new_target_cancel" class="btn btn-light me-3">Reset</button>
                        <button type="submit" class="btn btn-primary"><span class="indicator-label">Submit</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Format options
        var optionFormat = function(item) {
            if ( !item.id ) {
                return item.text;
            }

            var span = document.createElement('span');
            var imgUrl = item.element.getAttribute('data-kt-select2-icon');
            var template = '';

            template += '<i class="'+imgUrl+' rounded-circle h-20px me-2"></i>';
            template += item.text;

            span.innerHTML = template;

            return $(span);
        }

        // Init Select2 --- more info: https://select2.org/
        $('#kt_docs_select2_country').select2({
            templateSelection: optionFormat,
            templateResult: optionFormat
        });
    </script>
@endpush
