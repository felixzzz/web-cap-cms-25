@php
$oldData = old($component);
$inputValue = ( !empty($oldData) )? $oldData : $value;
@endphp
<div class="form-group">
    <label class="col-form-label font-weight-bold">{{ $field['label'] }}</label>
    <select
        value="{{ $inputValue }}"
        name="{{ $component }}"
        class="form-control form-control-sm">
        @foreach($options as $option)
            @php($selected = $inputValue == $option['value'] ? 'selected' : '')
            <option value="{{$option['value']}}" {{$selected}}>{{$option['label']}}</option>
        @endforeach
    </select>
</div>
