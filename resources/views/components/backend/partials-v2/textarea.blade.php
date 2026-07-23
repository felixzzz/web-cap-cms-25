@php
$oldData = old($component);
$inputValue = ( !empty($oldData) )? $oldData : $value;
@endphp
<div class="form-group">
    <label class="col-form-label font-weight-bold">{{ $field['label'] }}</label>
    <textarea
        id="{{ $aliasComponent }}"
        name="{{ $component }}"
        type="text"
        class="form-control"
        placeholder="{{ $field['label'] }}">{{ (!isset($index) ? $inputValue : null) }}</textarea>
    @if(isset($field['max']) && !empty($field['max']))
        <div class="form-text">{{ "Max " . $field['max'] . " Characters" }}</div>
    @endif
</div>
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            ClassicEditor
                .create( document.querySelector( '#{{ $aliasComponent }}' ),{
                    toolbar: [],
                    ckfinder: {
                        uploadUrl: "{{ route('admin.image.upload').'?_token='.csrf_token()}}",
                    },
                    mediaEmbed: {
                        previewsInData:true
                    },
                })
                .catch( error => {
                    console.error( error );
                });
        });
    </script>
@endpush
