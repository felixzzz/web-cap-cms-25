@php
$inputValue = (old($component) !== null)? old($component) : $value;
$province = \App\Models\Province::find($inputValue);
@endphp
<div class="form-group">
    <label class="col-form-label font-weight-bold">{{ __($field['label']) }}</label>
    <input type="text" value="{{$province->name}}" class="form-control" readonly>
</div>
