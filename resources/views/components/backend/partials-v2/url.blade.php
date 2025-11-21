@php
$inputValue = (old($component) !== null)? old($component) : $value;
@endphp
<div class="form-group">
    <label class="col-form-label font-weight-bold">{{ __($field['label']) }}</label>
    <div class="controls">
        <div class="input-prepend input-group">
            <span class="input-group-text">{{config('app.front_url')}}</span>
            <input
                @if(isset($index))
                    x-model="field.{{ $field['name'] }}"
                    x-bind:name="'{{ $component }}[' + index + '][{{ $field['name'] }}]'"
                @else
                    value="{{ $inputValue }}"
                @endif

                name="{{ $component }}"
                type="text"
                class="form-control"
                placeholder=""
                size="16" />
        </div>
    </div>
</div>
