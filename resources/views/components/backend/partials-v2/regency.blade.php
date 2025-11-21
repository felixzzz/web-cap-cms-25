@php
$inputValue = (old($component) !== null)? old($component) : $value;
$regency = [];
if ($inputValue){
    $selectedRegency = \App\Models\Regency::find($inputValue);
    $regency = \App\Models\Regency::where('province_id',$selectedRegency->province_id)->get();
}
@endphp
<div class="form-group">
    <label class="col-form-label font-weight-bold">{{ __($field['label']) }}</label>
    <div class="controls">
        <select name="{{ $component }}" id="regency_id" class="form-control">
            @foreach($regency AS $item)
                <option value="{{$item->id}}" {{$item->id == $inputValue ? 'selected' : ''}}>{{$item->name}}</option>
            @endforeach
        </select>
    </div>
</div>

@push('after-scripts')

@endpush