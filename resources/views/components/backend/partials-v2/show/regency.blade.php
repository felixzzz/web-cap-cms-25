@php
$inputValue = (old($component) !== null)? old($component) : $value;
$regency = \App\Models\Regency::find($inputValue);
@endphp
<div class="form-group">
    <label class="col-form-label font-weight-bold">{{ __($field['label']) }}</label>
    <input type="text" value="{{$regency->name}}" class="form-control" readonly>
</div>
