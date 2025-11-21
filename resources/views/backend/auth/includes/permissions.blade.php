<div class="row mb-8">
    <div class="col-xl-3">
        <label for="permissions" class="form-label">@lang('Additional Permissions')</label>
    </div>
    <div class="col-xl-9 fv-row fv-plugins-icon-container">
        @include('backend.auth.role.includes.no-permissions-message')

        <div x-show="userType === '{{ $model::TYPE_ADMIN }}'">
            @include('backend.auth.includes.partials.permission-type', ['type' => $model::TYPE_ADMIN])
        </div>

        <div x-show="userType === '{{ $model::TYPE_USER}}'">
            @include('backend.auth.includes.partials.permission-type', ['type' => $model::TYPE_USER])
        </div>
    </div>
</div><!--form-group-->
