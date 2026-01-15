<template>
    <div class="card card-custom card-bordered repeater mt-6">
        <div class="card-header">
            <div class="card-title">
                {{ field.label }}
            </div>

            <input type="hidden" v-bind:name="component" :value="JSON.stringify(lists)" />
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-icon btn-primary" @click="openModal">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">
            <draggable v-model="lists" @end="onDragEnd" :key="lists.length" handle=".drag-handle" class="accordion"
                id="accordionRepeater">
                <div class="accordion-item" v-for="(list, no) in lists" :key="no">
                    <h2 class="accordion-header" :id="'heading' + no">
                        <button class="accordion-button collapsed d-flex align-items-center" type="button"
                            data-bs-toggle="collapse" :data-bs-target="'#collapse' + no" aria-expanded="false"
                            :aria-controls="'collapse' + no">
                            <span class="drag-handle me-3 stop-propagation" style="cursor: move;" @click.stop>
                                <i class="fas fa-bars"></i>
                            </span>
                            <span class="flex-grow-1">
                                {{ list.title || 'Item ' + (no + 1) }}
                            </span>
                            <span class="ms-auto me-3 stop-propagation" @click.stop>
                                <a href="javascript:;" class="btn btn-sm btn-icon btn-light-primary me-2"
                                    @click="editList(list, no)">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="javascript:;" class="btn btn-sm btn-icon btn-light-danger"
                                    @click="removeList(list, no)">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </span>
                        </button>
                    </h2>
                    <div :id="'collapse' + no" class="accordion-collapse collapse" :aria-labelledby="'heading' + no"
                        data-bs-parent="#accordionRepeater">
                        <div class="accordion-body">
                            <div class="row align-items-center">
                                <div class="col-md-3" v-for="col in field.list" :key="col.name">
                                    <label class="fw-bold text-muted">{{ col.label }}</label>
                                    <div v-if="col.type === 'image'">
                                        <img v-if="list[col.name]" :src="url + '/' + list[col.name]"
                                            style="height: 50px" class="rounded" />
                                        <span v-else class="text-gray-400">No Image</span>
                                    </div>
                                    <div v-else-if="col.type === 'editor' || col.type === 'editor_simple'">
                                        <span v-html="list[col.name] ? list[col.name] : '-'"></span>
                                    </div>
                                    <div v-else>
                                        <span class="text-dark fw-bolder text-hover-primary mb-1 fs-6">
                                            {{ list[col.name] ? list[col.name] : '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </draggable>
        </div>

        <div class="modal fade" tabindex="-1" :class="{ show: showModal }" :style="showModal ? 'display: block' : ''">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ modeEdit ? 'Edit' : 'Add' }} {{ field.label }}
                        </h5>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" @click="showModal = false">
                            <span class="svg-icon svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                        transform="rotate(-45 6 17.3137)" fill="black" />
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                        transform="rotate(45 7.41422 6)" fill="black" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row mb-5" v-for="col in field.list" v-bind:key="col.name">
                            <label class="col-md-3 col-form-label">{{ col.label }}</label>
                            <div class="col-md-9">
                                <template v-if="col.type == 'text'">
                                    <input type="text" class="form-control" v-model="formModal[col.name]" />
                                </template>
                                <template v-if="col.type == 'number'">
                                    <input type="number" class="form-control" v-model="formModal[col.name]" />
                                </template>
                                <template v-if="col.type == 'date'">
                                    <input type="date" class="form-control" v-model="formModal[col.name]" />
                                </template>
                                <template v-if="col.type == 'textarea'">
                                    <textarea class="form-control" rows="3" v-model="formModal[col.name]"></textarea>
                                </template>
                                <template v-if="col.type == 'select'">
                                    <select class="form-select" v-model="formModal[col.name]">
                                        <option value="">Select {{ col.label }}</option>
                                        <option v-for="option in col.options" v-bind:key="option.value"
                                            :value="option.value">
                                            {{ option.label }}
                                        </option>
                                    </select>
                                </template>
                                <template v-if="col.type == 'image'">
                                    <div class="image-input image-input-outline">
                                        <div class="image-input-wrapper w-125px h-125px"
                                            :style="{ 'background-image': 'url(' + (imageSrc ? imageSrc : (formModal[col.name] ? url + '/' + formModal[col.name] : '/img/no-image.jpg')) + ')' }">
                                        </div>
                                        <label
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                            title="Change avatar">
                                            <i class="bi bi-pencil-fill fs-7"></i>
                                            <input type="file" name="avatar" accept=".png, .jpg, .jpeg"
                                                @change="changeImage($event, col.name)" />
                                            <input type="hidden" name="avatar_remove" />
                                        </label>
                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                            title="Cancel avatar">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                            title="Remove avatar" @click="removeImage($event, col.name)">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                    </div>
                                    <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                                </template>
                                <template v-if="col.type == 'editor'">
                                    <div :id="aliascomponent + '_' + col.name"></div>
                                </template>
                                <template v-if="col.type == 'editor_simple'">
                                    <div :id="aliascomponent + '_' + col.name"></div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" @click="showModal = false">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-primary" @click="modeEdit ? updateList() : submitList()">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show" v-if="showModal"></div>
    </div>
</template>

<script>
import draggable from "vuedraggable";

export default {
    components: {
        draggable
    },
    props: [
        'url',
        'field',
        'value',
        'component',
        'aliascomponent',
        'editor_fields',
        'editor_simple_fields',
        'locale'
    ],
    data() {
        return {
            feUrl: '',
            showModal: false,
            formModal: {},
            lists: [],
            modeEdit: false,
            formIndex: '',
            allowableTypes: ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'avi', 'mov', 'wmv', 'flv'],
            maximumSize: 26214400,
            allowableImageTypes: ['jpg', 'jpeg', 'webp', 'png'],
            allowableDocsTypes: ['docs', 'pdf'],
            maxImageSize: 500 * 1024,
            maxVideoSize: 35 * 1024 * 1024,
            selectedImage: null,
            size: null,
            imageSrc: '',
            whatsapp: [],
            colorPalette: [],
            allCkEditors: [],
            products: [],
            subsidiary_list: [],
        }
    },
    mounted() {
        this.fetchProducts()
        this.generateFormModal()
        if (this.value) {
            this.lists = JSON.parse(this.value)
        }
    },

    methods: {
        onDragEnd(event) {
            console.log("Drag ended", event);
            console.log(this.lists)
        },
        fetchProducts() {
            axios.get('/api/products/json')
                .then(response => {
                    this.products = response.data.map(product => ({
                        value: product.slug,
                        label: product.title
                    }));
                })
                .catch(error => {
                    console.error('Error fetching products:', error);
                });
        },
        getEditorFields(list) {
            let editor_fields = [];
            list.forEach(col => {
                if (col.type === 'editor') {
                    editor_fields.push(col.name);
                }
            });
            return editor_fields;
        },
        getEditorSimpleFields(list) {
            if (!list) return [];
            let editor_simple_fields = [];
            list.forEach(col => {
                if (col.type === 'editor_simple') {
                    editor_simple_fields.push(col.name);
                }
            });
            return editor_simple_fields;
        },
        openModal() {
            this.showModal = true
            this.resetFrom()
            this.generateFormModal()
            this.generateEditor()
        },
        resetFrom() {
            this.modeEdit = false
            this.formIndex = ''
            this.selectedImage = ''
            this.imageSrc = ''
        },
        generateFormModal() {
            this.formModal = {}
            if (this.field && this.field.list) {
                this.field.list.forEach(item => {
                    this.formModal[item.name] = ''
                });
            }
            this.subsidiary_list = []
        },
        generateEditor() {
            this.$nextTick(() => {
                if (this.editor_fields) {
                    this.editor_fields.forEach(editor_field => {
                        if (!document.querySelector('#' + this.aliascomponent + '_' + editor_field)) return;
                        ClassicEditor
                            .create(document.querySelector('#' + this.aliascomponent + '_' + editor_field), {
                                ckfinder: {
                                    uploadUrl: '/antiadmin/image-upload',
                                },
                                mediaEmbed: {
                                    previewsInData: true
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
                                }
                            })
                            .then(editor => {
                                this.allCkEditors[editor_field] = editor;
                                if (this.modeEdit && this.formModal[editor_field]) {
                                    editor.setData(this.formModal[editor_field]);
                                }
                            })
                            .catch(error => {
                                console.error(error);
                            });
                    });
                }
                if (this.editor_simple_fields) {
                    this.editor_simple_fields.forEach(editor_field => {
                        if (!document.querySelector('#' + this.aliascomponent + '_' + editor_field)) return;
                        ClassicEditor
                            .create(document.querySelector('#' + this.aliascomponent + '_' + editor_field), {
                                ckfinder: {
                                    uploadUrl: '/antiadmin/image-upload',
                                },
                                mediaEmbed: {
                                    previewsInData: true
                                },
                                toolbar: [],
                            })
                            .then(editor => {
                                this.allCkEditors[editor_field] = editor;
                                if (this.modeEdit && this.formModal[editor_field]) {
                                    editor.setData(this.formModal[editor_field]);
                                }
                            })
                            .catch(error => {
                                console.error(error);
                            });
                    });
                }
            });
        },
        prepareDataEditor() {
            if (this.editor_fields) {
                this.editor_fields.forEach(editor_field => {
                    if (this.allCkEditors[editor_field])
                        this.formModal[editor_field] = this.allCkEditors[editor_field].getData()
                })
            }
            if (this.editor_simple_fields) {
                this.editor_simple_fields.forEach(editor_field => {
                    if (this.allCkEditors[editor_field])
                        this.formModal[editor_field] = this.allCkEditors[editor_field].getData()
                })
            }
        },
        submitList() {
            this.prepareDataEditor()
            if (this.subsidiary_list?.length) {
                this.formModal.subsidiary_list = this.subsidiary_list
            }
            this.lists.push({ ...this.formModal }) // Clone object to avoid reference issues
            this.showModal = false
        },
        removeList(list, index) {
            Swal.fire({
                title: 'Are you sure you want to delete this item?',
                showCancelButton: true,
                confirmButtonText: 'Confirm Delete',
                cancelButtonText: 'Cancel',
                icon: 'warning'
            }).then((result) => {
                if (result.value) {
                    this.lists.splice(index, 1)
                }
            });
        },
        editList(list, index) {
            this.showModal = true
            this.modeEdit = true
            this.imageSrc = ''
            this.formIndex = index
            this.generateFormModal()
            this.generateEditor()
            this.formModal = { ...list } // Clone object
            if (list.image) {
                this.imageSrc = this.url + "/" + list.image
            }
        },
        updateList() {
            this.prepareDataEditor()
            if (this.subsidiary_list?.length) {
                this.formModal.subsidiary_list = this.subsidiary_list
            }
            this.lists.splice(this.formIndex, 1, { ...this.formModal })
            this.formIndex = ''
            this.showModal = false
            this.modeEdit = false
            this.formModal = {}
        },
        validate(image) {
            const extension = image.name.split('.').pop().toLowerCase();
            const isImage = this.allowableImageTypes.includes(extension);
            const isDocs = this.allowableDocsTypes.includes(extension);

            if (!isImage && !isDocs) {
                alert(`Sorry, you can only upload ${this.allowableImageTypes.join(', ').toUpperCase()} images or ${this.allowableDocsTypes.join(', ').toUpperCase()} document.`);
                return false;
            }
            if (isImage && image.size > this.maxImageSize) {
                alert(`Upload failed, please use an image under 500 KB.`);
                return false;
            }

            if (isDocs && image.size > this.maxVideoSize) {
                alert(`Upload failed, please use a file under 35 MB.`);
                return false;
            }

            return true
        },
        changeImage($event, name) {
            this.selectedImage = $event.target.files[0]
            if (!this.validate(this.selectedImage))
                return
            const form = new FormData();
            form.append('image', this.selectedImage);
            let self = this
            window.axios.post('/antiadmin/page/image', form)
                .then(response => {
                    const res = response.data
                    self.formModal[name] = res.path
                    self.imageSrc = res.full_path
                })
                .catch(err => console.log(err));

        },
        removeImage($event, name) {
            let self = this
            self.formModal[name] = ''
            self.imageSrc = ''
        }
    }
}
</script>

<style scoped>
.accordion-button:not(.collapsed) {
    background-color: #f5f8fa;
    color: #181c32;
}

.stop-propagation {
    cursor: pointer;
}
</style>
