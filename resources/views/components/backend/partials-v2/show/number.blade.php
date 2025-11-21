@php
$oldData = old($component);
$inputValue = ( !empty($oldData) )? $oldData : $value;
@endphp
<div class="form-group">
    <label class="col-form-label font-weight-bold">{{ $field['label'] }}</label>
    <input
        value="{{ $inputValue ? $inputValue : ($loopIndex + 1) }}"
        type="number"
        class="form-control"
        placeholder="{{ $field['label'] }}"
        maxlength="255" readonly/>
</div>
