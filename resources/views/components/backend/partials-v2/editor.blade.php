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
                    ckfinder: {
                        uploadUrl: "{{ route('admin.image.upload').'?_token='.csrf_token()}}",
                    },
                    mediaEmbed: {
                        previewsInData:true
                    },
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'link', '|',
                        'bulletedList', 'numberedList', '|',
                        'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', '|',
                        'undo', 'redo'
                    ],
                    heading: {
                        options: [
                            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                            { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                            { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                            { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                        ]
                    },
                    link: {
                        defaultProtocol: 'https://',
                        decorators: {
                            openInNewTab: {
                                mode: 'manual',
                                label: 'Open link in a new tab',
                                attributes: {
                                    target: '_blank',
                                    rel: 'noopener noreferrer'
                                }
                            }
                        }
                    }
                })
                .catch( error => {
                    console.error( error );
                });
        });
    </script>
@endpush
