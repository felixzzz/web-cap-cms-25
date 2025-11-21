@php
$inputValue = (old($component) !== null)? old($component) : $value;
@endphp
<div class="form-group">
    <label class="col-form-label font-weight-bold">{{ __($field['label']) }}</label>
    <div class="controls">
        <a href="{{route('admin.component.index',$inputValue)}}">Go To {{ __($field['label']) }}</a>
    </div>
</div>
