@php
$inputValue = (old($component) !== null)? old($component) : $value;
@endphp
<div class="form-group">
    <label class="col-form-label font-weight-bold">{{ __($field['label']) }}</label>
    <div class="controls">
        <input type="color" value="{{$inputValue}}" class="form-control" disabled>
    </div>
</div>