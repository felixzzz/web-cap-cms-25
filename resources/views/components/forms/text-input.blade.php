@props([
    'type' => 'text',
    'isSide' => '0',
    'required' => '0',
    'disabled' => '0',
    'hidden' => '0',
    'label' => 'labeling',
    'name' => 'naming',
    'placeholder' => '',
    'value' => '',
    'text' => '',
    'maxlength' => '',
])

@php
    $find = [ '[', ']'];
    $replace = [ '.', ''];
    $nameParse = str_replace($find, $replace, $name);
@endphp

@if ($isSide)
    <div class="row mb-8" {{ ((bool) $hidden) ? 'style=display:none;' : '' }}>
        <div class="col-xl-3">
            <div class="fs-6 fw-semibold mt-2 mb-3 {{ ((bool) $required) ? 'required' : ''}}">{{ $label }}</div>
        </div>
        <div class="col-xl-9 fv-row fv-plugins-icon-container">

            <input type="{{ $attributes->get('type') ?? $type }}"
                class="form-control"
                name="{{ $name }}"
                placeholder="{{ $placeholder }}"
                maxlength="{{ $maxlength }}"
                value="{{ old($nameParse, $value) }}"
                {{ ((bool) $required) ? 'required' : '' }}>

            @if ($text != '')
            <div class="text-muted fs-7">{{ $text }}</div>
            @endif
            @error($nameParse)
            <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
@else
    <div class="mb-8 fv-row fv-plugins-icon-container" {{ ((bool) $hidden) ? 'style=display:none;' : '' }}>
        <label class="{{ ((bool) $required) ? 'required' : '' }} form-label">{{ $label }}</label>
        <input type="{{ $type }}" class="form-control mb-2" name="{{ $name }}" placeholder="{{ $placeholder }}" maxlength="{{ $maxlength }}" value="{{ old($nameParse, $value) }}" {{ ((bool) $required) ? 'required' : '' }} {{ ((bool) $disabled) ? 'disabled' : '' }}>
        @if ($text != null)
        <div class="text-muted fs-7">{{ $text }}</div>
        @endif
        @error($nameParse)
        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
@endif
