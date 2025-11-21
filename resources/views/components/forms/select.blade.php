<div class="mb-8 fv-row fv-plugins-icon-container" {{ ((bool) $hidden) ? 'style=display:none;' : '' }}>
    <label class="form-label {{ $required ? 'required' : '' }}">{{ $label }}</label>
    <select class="form-control mb-2" name="{{ $name }}" data-control="select2" data-placeholder="{{ ($placeholder != null) ? $placeholder : 'Select an option' }}" data-allow-clear="true" {{ ((bool)$multiple) ? 'multiple="multiple"' : '' }} {{ ((bool) $required) ? 'required' : '' }} >
        {{ $slot }}
    </select>
    <div class="text-muted fs-7">{{ $text }}</div>
    @error($name)
    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
    @enderror
</div>
