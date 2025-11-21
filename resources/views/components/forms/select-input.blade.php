@props([
    'required' => 0,
    'label' => null,
    'name' => null,
    'placeholder' => null,
    'multiple' => 0,
    'options' => [],
    'text' => null
])

<div class="mb-8 fv-row fv-plugins-icon-container">
    <label class="{{ ((bool) $required) ? 'required' : '' }} form-label">{{ $label }}</label>
    <select class="form-select form-select-solid mb-2" name="{{ $name }}" data-control="select2" data-placeholder="{{ ($placeholder != null) ? $placeholder : 'Select an option' }}" data-allow-clear="true" {{ ((bool)$multiple) ? 'multiple="multiple"' : '' }} {{ ((bool) $required) ? 'required' : '' }} >
        {{ $slots  }}
    </select>
    <div class="text-muted fs-7">{{ $text }}</div>
    @error($name)
    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
    @enderror
</div>
