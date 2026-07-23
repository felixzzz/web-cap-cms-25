@props([
    'required' => '0',
    'label' => 'labeling',
    'name' => 'naming',
    'value' => '',
    'text' => '',
    'hidden' => '0',
])

<div class="mb-8 fv-row fv-plugins-icon-container"  {{ ((bool) $hidden) ? 'style=display:none;' : '' }}>
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
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            ClassicEditor
                .create( document.querySelector( '#{{$name}}' ),{
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



