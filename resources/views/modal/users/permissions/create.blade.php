<div class="modal fade" id="kt_modal_add_permission" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bolder">Add a Permission</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-permissions-modal-action="close">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <form method="post" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('permissions.store') }}">
                    @csrf
                    <div class="fv-row mb-7 fv-plugins-icon-container">
                        <label class="fs-6 fw-bold form-label mb-2">
                            <span class="required">Permission Name</span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="Permission names is required to be unique." data-bs-original-title="" title=""></i>
                        </label>
                        <input class="form-control form-control-solid" placeholder="Enter a permission name" name="name">
                        <div class="fv-plugins-message-container invalid-feedback"></div>
                    </div>
                    <div class="text-gray-600">Permission is the
                        <strong class="me-1">Core</strong>of each rules. Make sure the name of it is unique
                    </div>
                    <div class="text-center pt-15">
                        <button type="submit" class="btn btn-primary" data-kt-permissions-modal-action="submit">
                            <span class="indicator-label">Submit</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
