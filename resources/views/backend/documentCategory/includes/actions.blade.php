@if ($model->trashed())
    @if ($logged_in_user->can($permission.'.delete'))
        <div style="min-width: 80px">
            <x-utils.form-button-icon
                    :action="route($route.'.restore', ['category' => $model])"
                    method="patch"
                    button-class="btn btn-icon btn-bg-light btn-sm"
                    name="confirm-item"
            >
                <span class="svg-icon svg-icon-3">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.5 20.7259C14.6 21.2259 14.2 21.826 13.7 21.926C13.2 22.026 12.6 22.0259 12.1 22.0259C9.5 22.0259 6.9 21.0259 5 19.1259C1.4 15.5259 1.09998 9.72592 4.29998 5.82592L5.70001 7.22595C3.30001 10.3259 3.59999 14.8259 6.39999 17.7259C8.19999 19.5259 10.8 20.426 13.4 19.926C13.9 19.826 14.4 20.2259 14.5 20.7259ZM18.4 16.8259L19.8 18.2259C22.9 14.3259 22.7 8.52593 19 4.92593C16.7 2.62593 13.5 1.62594 10.3 2.12594C9.79998 2.22594 9.4 2.72595 9.5 3.22595C9.6 3.72595 10.1 4.12594 10.6 4.02594C13.1 3.62594 15.7 4.42595 17.6 6.22595C20.5 9.22595 20.7 13.7259 18.4 16.8259Z" fill="currentColor"/>
                        <path opacity="0.3" d="M2 3.62592H7C7.6 3.62592 8 4.02592 8 4.62592V9.62589L2 3.62592ZM16 14.4259V19.4259C16 20.0259 16.4 20.4259 17 20.4259H22L16 14.4259Z" fill="currentColor"/>
                    </svg>
                </span>
            </x-utils.form-button-icon>

            <x-utils.delete-button
                    :href="route($route.'.permanently-delete', ['category' => $model])"
                    :text="__('Permanently Delete')" />
        </div>
    @endif
@else
    <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-primary btn-active-light-primary me-n3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" control-id="ControlID-23">
        <span class="svg-icon svg-icon-3 svg-icon-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                 <rect x="5" y="5" width="5" height="5" rx="1" fill="currentColor"></rect>
                    <rect x="14" y="5" width="5" height="5" rx="1" fill="currentColor" opacity="0.3"></rect>
                    <rect x="5" y="14" width="5" height="5" rx="1" fill="currentColor" opacity="0.3"></rect>
                    <rect x="14" y="14" width="5" height="5" rx="1" fill="currentColor" opacity="0.3"></rect>
                </g>
            </svg>
        </span>
    </button>
    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true" style="">
    <div class="menu-item px-3">
        <div class="menu-content fs-6 text-dark fw-bold px-3 py-4">Quick Actions</div>
    </div>
    <div class="separator mb-3 opacity-75"></div>
        <div class="menu-item px-3">
            <a href="{{ route('admin.document-categories.show', ['category' =>$model, 'template' => $template]) }}" class="menu-link px-3">
                View
            </a>
        </div>
        <div class="menu-item px-3">
            <a href="{{ route('admin.document-categories.edit', ['category' =>$model, 'template' => $template]) }}" class="menu-link px-3">
                Edit
            </a>
        </div>
    <div class="separator mt-3 opacity-75"></div>
    <div class="menu-item px-3">
        <div class="menu-content px-3 py-3">
            <div class="">
                <x-utils.delete-button :href="route('admin.document-categories.destroy', ['category' =>$model, 'template' => $template])" />
            </div>
        </div>
    </div>
</div>
@endif
