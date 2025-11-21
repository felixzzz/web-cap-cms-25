@props([
    'src' => null,
    'name' => null,
    'text' => null,
])

<div class="text-center mb-8">
    <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
        <div class="image-input-wrapper w-150px h-150px" style="background-image: url({{ ($src != null) ? $src : asset('media/svg/avatars/blank.svg') }})"></div>
        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" aria-label="Change avatar" data-bs-original-title="Change avatar" data-kt-initialized="1">
            <i class="bi bi-pencil-fill fs-7"></i>
            <input type="file" name="{{str_slug($name, '_')}}" accept=".png, .jpg, .jpeg">
            <input type="hidden" name="{{str_slug($name, '_')}}_remove">
        </label>
        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel avatar" data-bs-original-title="Cancel avatar" data-kt-initialized="1">
            <i class="bi bi-x fs-2"></i>
        </span>
        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove avatar" data-bs-original-title="Remove avatar" data-kt-initialized="1">
            <i class="bi bi-x fs-2"></i>
        </span>
    </div>
    @if ($text != null)
    <div class="text-muted fs-7">{{ $text }}</div>
    @endif
    @error(str_slug($name, '_'))
    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
    @enderror
</div>
