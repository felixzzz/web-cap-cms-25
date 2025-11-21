@php
$oldData = old($component);
$inputValue = ( !empty($oldData) )? $oldData : $value;
@endphp
<div class="form-group">
    <label class="col-form-label font-weight-bold">{{ $field['label'] }}</label>
    <input
        value="{{ $field['label'] == 'Status' ? ($inputValue ? "Enable" : "Disable") : $inputValue }}"
        type="text"
        class="form-control"
        placeholder="{{ $field['label'] }}"
        maxlength="255" readonly/>
</div>
