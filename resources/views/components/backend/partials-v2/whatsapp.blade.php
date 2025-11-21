@php
$inputValue = (old($component) !== null)? old($component) : $value;
$whatsapp = \App\Domains\Post\Models\Post::with('meta')->where('type','whatsapp')->where('status','published')->get();
@endphp
<div class="form-group">
    <label class="col-form-label font-weight-bold">{{ __($field['label']) }}</label>
    <div class="controls">
        <select name="{{ $component }}" id="province_id" class="form-control select2">
            <option value="">-- Whatsapp --</option>
            @foreach($whatsapp AS $item)
                <option value="{{$item->id}}" {{$item->id == $inputValue ? 'selected' : ''}}>{{$item->title}} - {{$item->meta[0]->value}}</option>
            @endforeach
        </select>
    </div>
    <small for=""><a href="{{route('admin.component.index','whatsapp')}}">Go to whatsapp contact</a></small>
</div>

@push('after-scripts')

@endpush