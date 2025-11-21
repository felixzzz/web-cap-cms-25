@props([
    'required' => '0',
    'label' => 'labeling',
    'hidden' => '0',
    'name' => 'naming',
    'placeholder' => '',
    'value' => null,
    'text' => null,
    'maxlength' => '',
])

@php
    $find = [ '[', ']', '-'];
    $replace = [ '.', '', '_'];
    $nameParse = str_replace($find, $replace, $name);
@endphp

<div class="mb-8 fv-row fv-plugins-icon-container" {{ ((bool) $hidden) ? 'style=display:none;' : '' }}>
    <label class="{{ ((bool) $required) ? 'required' : '' }} form-label">{{ $label }}</label>
    <textarea
        name="{{ $nameParse }}"
        class="form-control mb-2"
        cols="10" rows="3"
        maxlength="{{ $maxlength }}"
        placeholder="{{ $placeholder }}"
        {{ ((bool) $required) ? 'required' : '' }}>{{ old($name, $value) }}</textarea>
    <div class="text-muted fs-7">{{ $text }}</div>
    @error($nameParse)
    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
    @enderror
</div>
