@php
$inputValue = (old($component) !== null)? old($component) : $value;
@endphp
<div class="form-group">
    <label class="col-form-label font-weight-bold">{{ __($field['label']) }}</label>
    <div class="controls">
        <div class="input-prepend input-group">
            <div class="input-group-prepend"><span class="input-group-text">URL</span></div>
            <input
                @if(isset($index))
                    x-model="field.{{ $field['name'] }}"
                @else
                    value="{{ $inputValue }}"
                @endif

                name="{{ $component }}"
                type="url"
                class="form-control"
                placeholder=""
                readonly
                size="16" />
        </div>
        <p class="help-block">ex: https://example.com</p>
    </div>
</div>
