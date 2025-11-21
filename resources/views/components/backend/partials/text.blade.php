@php
$oldData = old($component);
$inputValue = ( !empty($oldData) )? $oldData : $value;
@endphp
<div class="form-group">
    <label class="col-form-label">{{ $field['label'] }}</label>
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
        maxlength="255" />
</div>
