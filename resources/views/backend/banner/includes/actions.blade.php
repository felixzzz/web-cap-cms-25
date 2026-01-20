<button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-primary btn-active-light-primary me-n3"
    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
    <span class="svg-icon svg-icon-3 svg-icon-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <rect x="5" y="5" width="5" height="5" rx="1" fill="currentColor"></rect>
                <rect x="14" y="5" width="5" height="5" rx="1" fill="currentColor" opacity="0.3">
                </rect>
                <rect x="5" y="14" width="5" height="5" rx="1" fill="currentColor" opacity="0.3">
                </rect>
                <rect x="14" y="14" width="5" height="5" rx="1" fill="currentColor" opacity="0.3">
                </rect>
            </g>
        </svg>
    </span>
</button>
<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px"
    data-kt-menu="true">
    <div class="menu-item px-3">
        <div class="menu-content fs-6 text-dark fw-bold px-3 py-4">Quick Actions</div>
    </div>
    <div class="separator mb-3 opacity-75"></div>

    <div class="menu-item px-3">
        <a href="{{ route('admin.banner.edit', $model) }}" class="menu-link px-3">
            @lang('Edit')
        </a>
    </div>
    <div class="menu-item px-3">
        <a href="#" class="menu-link px-3" wire:click="$emit('openBannerEmbed', {{ $model->id }})">
            @lang('Embed')
        </a>
    </div>
    <div class="separator mb-3 opacity-75"></div>
    <div class="menu-item px-3 pb-3">
        <x-utils.delete-button :href="route('admin.banner.destroy', $model)" :text="('Delete')" />
    </div>
</div>