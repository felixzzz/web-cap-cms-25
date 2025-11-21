@push('after-styles')
    <style>
        .filepond--credits {
            display: none !important;
        }
    </style>
@endpush

<div class="{{ ($class ?? 'mb-8 fv-row fv-plugins-icon-container') }}">
    <div id="viewUpload">
        <label class="{{ $required ? 'required' : '' }} form-label">
            {{ $label }}
            @if ($src)
                (<a href="{{ $src }}" target="_blank">View Uploaded</a>)
            @endif
        </label>
        @if ($src)
            <div class="form-group row">
                <!-- Display a preview if needed, though for document files this might be a generic placeholder -->
                <div class="col-md-12">
                    <a href="{{ $src }}" target="_blank">View Uploaded File</a>
                </div>
            </div>
        @endif
    </div>
    <input type="file" class="form-control-sm" id="{{ str_slug($name, '_') }}" name="{{ $name }}" {{ $required ? 'required' : '' }}>
    @if ($text)
        <div class="text-muted fs-7">{{ $text }}</div>
    @endif
</div>

@push('scripts')
    <script>
        const fileInput = document.getElementById('{{ str_slug($name, '_') }}');

        // Register FilePond plugins
        FilePond.registerPlugin(FilePondPluginFileValidateType);

        // Create FilePond instance
        const pond = FilePond.create(fileInput, {
            acceptedFileTypes: ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
            fileValidateTypeLabelExpectedTypes: 'Expected {allButLastType} or {lastType}',
        });

        pond.onprocessfile = (error, file) => {
            if (error) {
                console.log('Error processing file', error);
                return;
            }
            console.log('File processed', file);
            document.getElementById('viewUpload').style.display = 'none';
        };

        // FilePond server configuration
        FilePond.setOptions({
            server: {
                url: '{{ route('ajax.filepond') }}',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            },
        });
    </script>
@endpush
