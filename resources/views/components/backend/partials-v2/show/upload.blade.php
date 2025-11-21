@php
    $valueObj = json_decode($value, true);
    $url = '';
    if ($valueObj && isset($valueObj['path'])){
        $url = \Illuminate\Support\Facades\Storage::url($valueObj['path']);
    }
@endphp
<div class="form-group">
    <label class="col-form-label font-weight-bold">{{ __($field['label']) }}</label>
    <div class="controls">
        <div class="input-group">
            @if($url)
                <a href="{{$url}}">{{$url}}</a>
            @else
                <span>-</span>
            @endif
        </div>
    </div>
</div>
