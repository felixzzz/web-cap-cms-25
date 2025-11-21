@php
$oldData = old($component);
$inputValue = ( !empty($oldData) )? $oldData : $value;
@endphp
<div class="form-group">
    <label class="col-form-label">{{ $field['label'] }}</label>
    <select
        @if(isset($index))
            x-model="field.{{ $field['name'] }}"
            x-bind:name="'{{ $component }}[' + index + '][{{ $field['name'] }}]'"
        @else
            value="{{ $inputValue }}"
        @endif

        name="{{ $component }}"
        class="form-control">
        @foreach($options as $option)
            @php($selected = $inputValue == $option['value'] ? 'selected' : '')
            <option value="{{$option['value']}}" {{$selected}}>{{$option['label']}}</option>
        @endforeach
    </select>
</div>
