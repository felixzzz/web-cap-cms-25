@props([
    'required' => '0',
    'label' => 'labeling',
    'name' => 'naming',
    'value' => '',
    'text' => '',
])

<div class="mb-8 fv-row fv-plugins-icon-container">
    <label class="{{ ((bool) $required) ? 'required' : '' }} form-label">{{ $label }}</label>
    <textarea id="{{$name}}" name="{{ $name }}" rows="3" cols="10">{!! old($name, $value) !!}</textarea>
    @if ($text != null)
    <div class="text-muted fs-7">{{ $text }}</div>
    @endif
    @error($name)
    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
    @enderror
</div>
@push('scripts')
    <script src="{{ asset('js/tinymce/tinymce.js') }}"></script>
    <script>
        tinymce.init({
            selector: 'textarea#{{$name}}',
            plugins: 'code table lists',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table'
        });
    </script>
@endpush



