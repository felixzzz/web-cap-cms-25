@php
$inputValue = (old($component) !== null)? old($component) : $value;
@endphp
<div class="form-group">
    <label class="col-form-label font-weight-bold">{{ __($field['label']) }}</label>
    <div class="controls">
        <div class="input-group">
            <span class="input-group-text">URL</span>
            <input
                @if(isset($index))
                    x-model="field.{{ $field['name'] }}"
                    x-bind:name="'{{ $component }}[' + index + '][{{ $field['name'] }}]'"
                @else
                    value="{{ $inputValue }}"
                @endif

                name="{{ $component }}"
                type="url"
                class="form-control"
                placeholder=""
                size="16" />
        </div>
        <p class="help-block">ex: https://example.com</p>
    </div>
</div>
