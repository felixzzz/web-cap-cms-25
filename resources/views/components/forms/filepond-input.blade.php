@push('after-styles')
    <style>
        .filepond--credits {
            display: none !important;
        }
    </style>
@endpush
<div class="{{ ($class != null) ? $class : 'mb-8 fv-row fv-plugins-icon-container' }}">
    <div id="viewUpload">
        <label class="{{ ((bool) $required) ? 'required' : '' }} form-label">
            {{ $label }}
            @if ($src != null)
                (<a href="{{ $src }}" target="_blank">View Uploaded</a>)
            @endif
        </label>
        @if ($src != null)
            <div class="form-group row">
                <img class="col-md-4" id="original-image" src="{{ $src }}" alt="">
                <img class="col-md-12" id="preview-image-before-upload" src="#" alt="">
            </div>
        @endif
    </div>
    <input type="file" class="form-control-sm" id="{{str_slug($name, '_')}}" name="{{ $name }}" {{ ((bool) $required) ? 'required' : '' }}>
    @if ($text != null)
        <div class="text-muted fs-7">{{ $text }}</div>
    @endif
</div>
@push('scripts')
    <script>
        const {{str_slug($name, '_')}} = document.getElementById('{{str_slug($name, '_')}}');

        FilePond.registerPlugin(FilePondPluginFileValidateType);
        FilePond.registerPlugin(FilePondPluginImagePreview);

        const pond = FilePond.create( {{str_slug($name, '_')}}, {
            acceptedFileTypes: ['image/png', 'image/jpg', 'image/webp', 'image/jpeg'],
        });

        pond.onprocessfile = (error, file) => {
            if (error) {
                console.log('Oh no');
                return;
            }
            console.log('File processed', file);
            $('#viewUpload').hide();
        }

        // Register the plugin
        FilePond.setOptions({
            server: {
                url: '{{ route('ajax.filepond') }}',
                headers: {
                    'X-CSRF-TOKEN' : token,
                }
            },
        });

    </script>
@endpush
