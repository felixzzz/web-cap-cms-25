@php
$inputValue = (old($component) !== null)? old($component) : $value;
$province = \App\Models\Province::get();
@endphp
<div class="form-group">
    <label class="col-form-label font-weight-bold">{{ __($field['label']) }}</label>
    <div class="controls">
        <select name="{{ $component }}" id="province_id" class="form-control select2">
            @foreach($province AS $item)
                <option value="{{$item->id}}" {{$item->id == $inputValue ? 'selected' : ''}}>{{$item->name}}</option>
            @endforeach
        </select>
    </div>
</div>

@push('after-scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        var select2Regency = false
        $('.select2').select2();
        $('#province_id').change(function (){
            var value = $(this).val();
            $.get('{{url('antiadmin/get-regency')}}/'+value)
                .then(function (data){
                    console.log(data)
                    var regency = data.map(function (val,i){
                        return `<option value="${val.id}">${val.name}</option>`
                    })
                    $("#regency_id").html(regency.join(""))
                    if (select2Regency)
                        $("#regency_id").select2("destroy")
                    $("#regency_id").select2();
                    select2Regency = true
                })
        })
    </script>
@endpush