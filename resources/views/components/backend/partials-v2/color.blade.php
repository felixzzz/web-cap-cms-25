@php
$inputValue = (old($component) !== null)? old($component) : $value;
$color = \App\Domains\Post\Models\Post::where('type','color')->where('status','published')->get();
@endphp
<div class="form-group">
    <label class="col-form-label font-weight-bold">{{ __($field['label']) }}</label>
    <div class="controls">
        <select name="{{ $component }}" class="form-control select2">
            <option value="">-- Color --</option>
            @foreach ($color AS $item)
            <option value="{{$item->title_en}}" style="background: {{$item->title_en}}">{{$item->title_id}}</option>
            @endforeach
        </select>
    </div>
</div>

@push('after-scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('.select2').select2({
            templateResult: function(option) {
                return $('<span style="' + ($(option.element).attr('style') || '') + '">' + option.text + '</span>');
                },
            templateSelection: function(option) {
                return $('<span style="' + ($(option.element).attr('style') || '') + '">' + option.text + '</span>');
                },
        });
    </script>
@endpush