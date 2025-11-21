<div class="modal fade" id="add_new_category" tabindex="-1" aria-modal="true" role="dialog" style="display: none; padding-left: 0px;">
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
                <form method="post" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('admin.category.store', ['type' => $type]) }}">
                    @csrf
                    <x-forms.text-input name="name" label="Category Name" required="1" placeholder="Technology"/>
                    <x-forms.textarea-input name="description" label="Category Description" required="1" placeholder="Latest news about Technology"/>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary"><span class="indicator-label">Create new Category</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
