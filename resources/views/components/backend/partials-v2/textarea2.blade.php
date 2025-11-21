@php
$oldData = old($component);
$inputValue = ( !empty($oldData) )? $oldData : $value;
$required = $field['required'] ?? null;
@endphp
<div class="form-group">
    <label class="col-form-label font-weight-bold">{{ __($field['label']) }}</label>
    <textarea
        @if(isset($index))
            x-model="field.{{ $field['name'] }}"
            x-bind:name="'{{ $component }}[' + index + '][{{ $field['name'] }}]'"
            x-bind:id="'{{ $component }}_' + index + '_{{ $field['name'] }}'"
        @else
            id="{{ $component }}"
        @endif
        name="{{ $component }}"
        type="text"
        class="form-control"
        maxlength="{{ isset($field['max']) ? $field['max'] : '500' }}"
        @if($required) required @endif 
        placeholder="{{ $field['label'] }}">{{ (!isset($index) ? $inputValue : null) }}</textarea>
    @if (isset($field['max']))
        <div class="form-text"> Max {{ $field['max'] }} Characters</div>
    @endif
</div>