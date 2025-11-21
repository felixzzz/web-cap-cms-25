@php
$oldData = old($component);
$inputValue = ( !empty($oldData) )? $oldData : $value;
@endphp
<div class="form-group">
    <label class="col-form-label font-weight-bold">{{ __($field['label']) }}</label>
    <input
        @if(isset($index))
            x-model="field.{{ $field['name'] }}"
            x-bind:name="'{{ $component }}[' + index + '][{{ $field['name'] }}]'"
        @else
            value="{{ $inputValue ?? '62'}}"
        @endif
        name="{{ $component }}"
        type="tel"
        class="form-control phone"
        placeholder="{{ $field['label'] }}. Example : 62851234567"
        maxlength="15" />
</div>
@push('after-scripts')
<script>
    $(document).ready(function (){
        var phone = $('.phone').val()
        if (!phone){
            $('.phone').val(62)
        }
        $('.phone').on('keyup',function (){
            var value = $(this).val()
            if (value.length >= 2) {
                if (!value.startsWith('62')) {
                    var phone = value.substring(2);
                    $(this).val(62+phone)
                }
            }
        })
    })
</script>
@endpush