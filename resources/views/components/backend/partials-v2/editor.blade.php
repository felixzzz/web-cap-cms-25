@php
    $oldData = old($component);
    $inputValue = (!empty($oldData)) ? $oldData : $value;
@endphp
<div class="form-group">
    <label class="col-form-label font-weight-bold">{{ $field['label'] }}</label>
    <textarea id="{{ $aliasComponent }}" name="{{ $component }}" type="text" class="form-control"
        placeholder="{{ $field['label'] }}">{{ (!isset($index) ? $inputValue : null) }}</textarea>
    @if(isset($field['max']) && !empty($field['max']))
        <div class="form-text">{{ "Max " . $field['max'] . " Characters" }}</div>
    @endif
</div>
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Adapted from public/create.blade.php
            function BannerRenderPlugin(editor) {
                // Define the schema
                editor.model.schema.register('embeddedBanner', {
                    isObject: true,
                    allowWhere: '$block',
                    allowAttributes: ['id', 'title', 'startDate', 'endDate']
                });

                // UPCAST: Convert <span class="check-banner-shortcode" ...> to embeddedBanner model
                editor.conversion.for('upcast').elementToElement({
                    view: {
                        name: 'span',
                        classes: 'check-banner-shortcode'
                    },
                    model: (viewElement, { writer: modelWriter }) => {
                        const id = viewElement.getAttribute('data-banner-id');
                        const title = viewElement.getAttribute('data-banner-title') || 'Banner ' + id;
                        const startDate = viewElement.getAttribute('data-start-date');
                        const endDate = viewElement.getAttribute('data-end-date');

                        if (!id) return null;

                        return modelWriter.createElement('embeddedBanner', {
                            id,
                            title,
                            startDate,
                            endDate
                        });
                    }
                });

                // EDITING DOWNCAST: Convert model to styled view for the editor (The Card View)
                editor.conversion.for('editingDowncast').elementToElement({
                    model: 'embeddedBanner',
                    view: (modelElement, { writer: viewWriter }) => {
                        const id = modelElement.getAttribute('id');
                        const title = modelElement.getAttribute('title');
                        const startDate = modelElement.getAttribute('startDate');
                        const endDate = modelElement.getAttribute('endDate');

                        // Styles copied/adapted from public/create.blade.php
                        const container = viewWriter.createContainerElement('div', {
                            class: 'banner-widget',
                            style: 'margin: 1rem 0; padding: 10px; border: 1px solid #16a34a; background: #f0fdf4; border-radius: 5px; display: flex; flex-direction: column; gap: 5px; user-select: none;'
                        });

                        const label = viewWriter.createRawElement('span', {
                            style: 'font-weight: bold; color: #166534;'
                        }, function (domElement) {
                            domElement.innerText = 'BANNER: ' + (title || 'ID ' + id);
                        });

                        const details = viewWriter.createRawElement('div', {
                            style: 'font-size: 0.8rem; color: #15803d;'
                        }, function (domElement) {
                            let info = `ID: ${id}`;
                            if (startDate) info += ` | Start: ${startDate}`;
                            if (endDate) info += ` | End: ${endDate}`;
                            domElement.innerText = info;
                        });

                        viewWriter.insert(viewWriter.createPositionAt(container, 0), label);
                        viewWriter.insert(viewWriter.createPositionAt(container, 1), details);

                        return container;
                    }
                });

                // DATA DOWNCAST: Convert model back to the output string ###banner###...
                editor.conversion.for('dataDowncast').elementToElement({
                    model: 'embeddedBanner',
                    view: (modelElement, { writer: viewWriter }) => {
                        const id = modelElement.getAttribute('id');
                        const title = modelElement.getAttribute('title');
                        const startDate = modelElement.getAttribute('startDate');
                        const endDate = modelElement.getAttribute('endDate');

                        return viewWriter.createRawElement('span', {
                            'class': 'check-banner-shortcode',
                            'data-banner-id': id,
                            'data-banner-title': title,
                            'data-start-date': startDate,
                            'data-end-date': endDate
                        }, function (domElement) {
                            domElement.innerText = `###banner###${id}###banner###`;
                        });
                    }
                });
            }

            function InsertBannerPlugin(editor) {
                editor.ui.componentFactory.add('insertBanner', locale => {
                    let ButtonView;
                    try {
                        const testView = editor.ui.componentFactory.create('undo');
                        ButtonView = testView.constructor;
                    } catch (e) {
                        console.warn('ButtonView fetch failed', e);
                        return null;
                    }

                    const view = new ButtonView(locale);

                    view.set({
                        label: 'Insert Banner',
                        withText: true,
                        tooltip: true
                    });

                    view.on('execute', () => {
                        openBannerModal(editor);
                    });

                    return view;
                });
            }

            function openBannerModal(editor) {
                document.dispatchEvent(new CustomEvent('set-active-editor', { detail: { editor: editor } }));

                if (window.jQuery && window.jQuery('#bannerEmbedModal').length) {
                    window.jQuery('#bannerEmbedModal').modal('show');
                } else {
                    const modalEl = document.getElementById('bannerEmbedModal');
                    if (modalEl) {
                        try {
                            const modal = new bootstrap.Modal(modalEl);
                            modal.show();
                        } catch (e) {
                            console.error('Failed to open modal with Bootstrap API:', e);
                        }
                    } else {
                        console.error('Banner Embed Modal not found!');
                    }
                }
            }

            ClassicEditor
                .create(document.querySelector('#{{ $aliasComponent }}'), {
                    extraPlugins: [BannerRenderPlugin, InsertBannerPlugin],
                    ckfinder: {
                        uploadUrl: "{{ route('admin.image.upload') . '?_token=' . csrf_token()}}",
                    },
                    mediaEmbed: {
                        previewsInData: true
                    },
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'link', '|',
                        'bulletedList', 'numberedList', '|',
                        'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', 'insertBanner', '|',
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
                .catch(error => {
                    console.error(error);
                });
        });
    </script>
@endpush