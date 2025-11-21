@php
$oldData = old($component);
$inputValue = ( !empty($oldData) )? $oldData : $value;
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
        class="form-control"
        placeholder="{{ $field['label'] }}" readonly>{{ (!isset($index) ? $inputValue : null) }}</textarea>
</div>
