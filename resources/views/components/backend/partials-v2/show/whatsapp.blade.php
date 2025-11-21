@php
$oldData = old($component);
$inputValue = ( !empty($oldData) )? $oldData : $value;
$whatsapp = optional(\App\Domains\Post\Models\Post::with('meta')->where('type','whatsapp')->where('status','published')->find($inputValue));
@endphp
<div class="form-group">
    <label class="col-form-label font-weight-bold">{{ __($field['label']) }}</label>
    <input
        @if(isset($index))
            x-model="field.{{ $field['name'] }}"
            x-bind:name="'{{ $component }}[' + index + '][{{ $field['name'] }}]'"
        @else
            value="{{ $whatsapp->title }} - {{isset($whatsapp->meta[0]) ? $whatsapp->meta[0]->value : ''}}"
        @endif

        type="text"
        class="form-control"
        placeholder="{{ $field['label'] }}"
        maxlength="255" readonly/>
</div>
