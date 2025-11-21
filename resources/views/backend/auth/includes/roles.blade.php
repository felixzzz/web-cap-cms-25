<div class="row mb-8">
    <div class="col-xl-3">
        <label for="roles" class="form-label">@lang('Roles')</label>
    </div>
    <div class="col-xl-9 fv-row fv-plugins-icon-container">
        <div x-show="userType === '{{ $model::TYPE_ADMIN }}'">
            @include('backend.auth.includes.partials.role-type', ['type' => $model::TYPE_ADMIN])
        </div>

        <div x-show="userType === '{{ $model::TYPE_USER }}'">
            @include('backend.auth.includes.partials.role-type', ['type' => $model::TYPE_USER])
        </div>
    </div>
</div><!--form-group-->
