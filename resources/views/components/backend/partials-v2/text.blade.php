@php
$oldData = old($component);
$inputValue = ( !empty($oldData) )? $oldData : $value;
$max = $field['max'] ?? null;
$required = $field['required'] ?? null;
@endphp
<div class="form-group">
    <label class="col-form-label font-weight-bold">{{ __($field['label']) }}</label>
    <input
        @if(isset($index))
            x-model="field.{{ $field['name'] }}"
            x-bind:name="'{{ $component }}[' + index + '][{{ $field['name'] }}]'"
        @else
            value="{{ $inputValue }}"
        @endif

        name="{{ $component }}"
        type="text"
        class="form-control"
        placeholder="{{ $field['label'] }}"
        maxlength="{{ $max ?? 200 }}"
        @if($required) required @endif />
    @if(isset($max))
        <div class="form-text">{{ "Max {$max} Characters" }}</div>
    @endif
</div>
