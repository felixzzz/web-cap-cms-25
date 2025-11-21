@props([
    'required' => '0',
    'name' => '',
    'label' => '',
    'checked' => '0',
    'text' => null
])

@php
    $find = [ '[', ']'];
    $replace = [ '.', ''];
    $nameParse = str_replace($find, $replace, $name);
    $id = str_slug($name, '_');
@endphp

<div class="row mb-8">
    <div class="col-xl-3">
        <div class="fs-6 fw-semibold mt-2 mb-3 {{ ((bool) $required) ? 'required' : '' }}">{{ $label }}</div>
    </div>
    <div class="col-xl-9">
        <div class="d-flex fw-semibold h-100">
            <div class="form-check form-check-custom form-check-solid me-9">
                <input class="form-check-input" type="checkbox" id="{{ $id }}" value="1" name="{{ $name }}" {{ ((bool) $checked) ? 'checked' : '' }} >
                @if ($text != null)
                    <label class="form-check-label ms-3" for="{{ $id }}">{{ $text }}</label>
                @endif
            </div>
        </div>
    </div>
</div>
