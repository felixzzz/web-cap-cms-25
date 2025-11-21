@php
$oldData = old($component);
$inputValue = ( !empty($oldData) )? $oldData : $value;
@endphp
<div class="form-group">
    <label class="col-form-label font-weight-bold">{{ $field['label'] }}</label>
    <textarea
        id="{{ $aliasComponent }}"
        type="text"
        class="form-control"
        placeholder="{{ $field['label'] }}" readonly>{{ (!isset($index) ? $inputValue : null) }}</textarea>
</div>
@if(!isset($index))
    @push('after-scripts')
        <script>
            $(document).ready(function() {
                window.ClassicEditor.replace(document.querySelector("#{{ $aliasComponent }}"), {
                    filebrowserUploadUrl: "/antiadmin/page/ckeditor?_token=" + window.csrf_token,
                    filebrowserUploadMethod: 'form'
                });
            });
        </script>
    @endpush
@endif
