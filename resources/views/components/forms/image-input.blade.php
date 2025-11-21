<div class="row mb-8">
    <div class="col-lg-8">
        <label class="{{ ((bool) $required) ? 'required' : '' }} form-label mb-2">{{ $label }}</label>
        <div class="separte mb-3"></div>
        <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('http://intresno.test/media/svg/avatars/blank.svg')">
            <div class="image-input-wrapper w-125px h-125px bgi-position-center" style="background-image: url('http://intresno.test/media/svg/avatars/blank.svg')"></div>
            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" aria-label="Change avatar" data-kt-initialized="1">
                <i class="bi bi-pencil-fill fs-7"></i>
                <input type="file" name="{{str_slug($name, '_')}}" accept=".png, .jpg, .jpeg">
                <input type="hidden" name="{{str_slug($name, '_')}}_remove">
            </label>
            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel avatar" data-kt-initialized="1">
                <i class="bi bi-x fs-2"></i>
            </span>
            @if ($src != null)
                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove avatar" data-bs-original-title="Remove avatar" data-kt-initialized="1">
                    <i class="bi bi-x fs-2"></i>
                </span>
            @endif
        </div>
        @if ($text != null)
            <div class="form-text">{{ $text }}</div>
        @endif
        @error(str_slug($name, '_'))
        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
