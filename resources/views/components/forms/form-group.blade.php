@props([
    'required' => 0,
    'label' => null,
    'name' => null,
])

<div class="form-group row mb-6">
    <label for="{{ $name }}" class="col-md-2 col-form-label {{ ((bool) $required) ? 'required' : ''}}">{{ $label }}</label>

    <div class="col-md-10">
        {{ $slot }}
    </div>
</div><!--form-group-->
