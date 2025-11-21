@php
$oldData = old($component);
$inputValue = ( !empty($oldData) )? $oldData : $value;
@endphp
<div class="form-group">
    <label class="col-form-label">{{ $field['label'] }}</label>
    <textarea
        @if(isset($index))
            x-model="field.{{ $field['name'] }}"
            x-bind:name="'{{ $component }}[' + index + '][{{ $field['name'] }}]'"
            x-bind:id="'{{ $component }}_' + index + '_{{ $field['name'] }}'"
        @else
            id="{{ $component }}"
        @endif
        name="{{ $component }}"
        type="text"
        class="form-control"
        placeholder="{{ $field['label'] }}">{{ (!isset($index) ? $inputValue : null) }}</textarea>
</div>
@if(!isset($index))
    @push('after-scripts')
        <script>
            window.ClassicEditor.create(document.querySelector("#{{ $component }}"))
            .then(editor => {
                editor.plugins.get("FileRepository").createUploadAdapter = loader => {
                    let MyUploadAdapter = window.MyUploadAdapter;
                    return new MyUploadAdapter(loader)
                }
            })
            .catch( error => {
                console.log( error );
            });
        </script>
    @endpush
@endif
