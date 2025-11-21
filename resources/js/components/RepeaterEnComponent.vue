<style>
 .ck-balloon-panel{z-index:9999 !important}
 .swal2-container {z-index:9999 !important}
</style>

<template>
    <div class="card card-custom card-bordered repeater mt-6">
        <div class="card-header">
            <div class="card-title">
                {{ field.label }} 
            </div>

            <input
                type="hidden"
                v-bind:name="component"
                :value="JSON.stringify(lists)"
            />
            <div class="card-toolbar">
                <button
                    type="button"
                    class="btn btn-sm btn-icon btn-primary"
                    @click="openModal()"
                >
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <draggable v-model="lists" @end="onDragEnd" :key="lists">
            <div
                class="row align-items-center"
                v-for="(list, no) in lists"
                :key="no"
            >
                <div class="col mt-4">
                    <table
                        class="table border table-row-bordered gy-2 gs-2 table-fit"
                        :aria-label="field.label + '-table'"
                    >
                        <tbody>
                            <tr v-for="(item, i) in field.list" :key="i">
                                <th width="20%" class="border-end" v-if="
                                        item.type != 'image' &&  item.type != 'upload'
                                    ">
                                    {{ item.label }}
                                </th>
                                <th width="20%" class="border-end" v-if="
                                       item.multilang_file
                                    ">
                                    {{ item.label }}
                                </th>
                                <template
                                    v-if="
                                        item.type == 'upload' && list[item.name] && item.multilang_file
                                    "
                                >
                                    <td>
                                        <a
                                            :href="url + '/' + list[item.name]"
                                        > {{list[item.name]}} </a>
                                    </td>
                                </template>
                                <template v-if="item.type == 'boolean'">
                                    <td
                                        v-html="
                                            list[item.name] == 1 ? 'Yes' : 'no'
                                        "
                                    ></td>
                                </template>
                                <template v-else-if="item.type == 'whatsapp'">
                                    <td
                                        v-html="
                                            whatsapp
                                                .map((val) => {
                                                    if (
                                                        val.id ===
                                                        list[item.name]
                                                    ) {
                                                        return (
                                                            val.contact +
                                                            '-' +
                                                            val.phone_number
                                                        );
                                                    }
                                                })
                                                .join('')
                                        "
                                    ></td>
                                </template>
                                <template v-else-if="item.type == 'color'">
                                    <td>
                                        <input
                                            type="color"
                                            class="form-control"
                                            :value="list[item.name]"
                                            disabled
                                        />
                                    </td>
                                </template>
                                <template v-else-if="item.type == 'select'">
                                    <td
                                        v-html="
                                            list[item.name]
                                                ? list[item.name]
                                                      .split('_')
                                                      .join(' ')
                                                      .toUpperCase()
                                                : ''
                                        "
                                    ></td>
                                </template>
                                <template v-else-if="item.type == 'select_product'">
                                    <td
                                        v-html="
                                            list[item.name]
                                                ? list[item.name]
                                                      .split('_')
                                                      .join(' ')
                                                      .toUpperCase()
                                                : ''
                                        "
                                    ></td>
                                </template>
                                <template v-else-if="item.type == 'repeater'">
                                    <div
                                        class="row align-items-center pt-1"
                                        v-for="(list2, no2) in list[item.name]"
                                        :key="no2"
                                    >
                                        <div class="col">
                                            <table
                                                class="table table-sm table-bordered table-fit"
                                                :aria-label="
                                                    item.label + '-table'
                                                "
                                            >
                                                <tbody>
                                                    <tr
                                                        v-for="(
                                                            item2, i
                                                        ) in item.list"
                                                        :key="i"
                                                    >
                                                        <th width="20%">
                                                            {{ item2.label }}
                                                        </th>
                                                        <template
                                                            v-if="
                                                                item2.type ==
                                                                'boolean'
                                                            "
                                                        >
                                                            <td
                                                                v-html="
                                                                    list2[
                                                                        item2
                                                                            .name
                                                                    ] == 1
                                                                        ? 'Yes'
                                                                        : 'no'
                                                                "
                                                            ></td>
                                                        </template>
                                                        <template v-else>
                                                            <td
                                                                v-html="
                                                                    (item2.type ==
                                                                    'url'
                                                                        ? feUrl
                                                                        : '') +
                                                                    list2[
                                                                        item2
                                                                            .name
                                                                    ]
                                                                "
                                                            ></td>
                                                        </template>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </template>
                                <template v-else>
                                    <td v-if="item.type != 'image' &&  item.type != 'upload'"  
                                        v-html="
                                            (item.type == 'url' ? feUrl : '') +
                                            list[item.name]
                                        "
                                    ></td>
                                </template>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-1">
                    <button
                        type="button"
                        class="btn btn-sm btn-info btn-icon"
                        @click="editList(list, no)"
                    >
                        <i class="fa fa-pencil-alt"></i>
                    </button>
                    <button
                        type="button"
                        class="btn btn-sm btn-danger btn-icon mt-1"
                        @click="removeList(list, no)"
                    >
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
            </draggable>
        </div>
        <div v-if="showModal">
            <transition name="modal">
                <div class="modal-mask">
                    <div class="modal modal-wrapper">
                        <div
                            class="modal-dialog modal-dialog-scrollable"
                            :class="field.modalSize ?? 'modal-xl'"
                            role="document"
                        >
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        {{ modeEdit ? "Edit" : "Create" }} entry
                                    </h5>
                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-dismiss="modal"
                                        aria-label="Close"
                                        @click="showModal = false"
                                    />
                                </div>
                                <div class="modal-body">
                                    <div class="col-md-12">
                                        <input
                                            type="hidden"
                                            v-model="formIndex"
                                        />
                                        <div
                                            class="form-group row mb-4"
                                            v-for="(item, i) in field.list"
                                            :key="i"
                                        >
                                            <label
                                                for="title"
                                                class="col-md-2 col-form-label" v-if="item.type != 'image' && !field.all_image &&  item.type != 'upload'">{{ item.label }}</label
                                            >
                                            <label
                                                for="title"
                                                class="col-md-2 col-form-label" v-if="item.multilang_file">{{ item.label }}</label
                                            >
                                            <div class="col-md-10">
                                                <template
                                                    v-if="item.type == 'text'"
                                                >
                                                    <input
                                                        :maxlength="item.max"
                                                        type="text"
                                                        v-model="
                                                            formModal[item.name]
                                                        "
                                                        class="form-control"
                                                    />
                                                    <div
                                                        class="form-text"
                                                        v-if="item.max"
                                                    >
                                                        {{
                                                            `Max ${item.max} Characters`
                                                        }}
                                                    </div>
                                                </template>
                                                <template
                                                    v-else-if="
                                                        item.type == 'boolean'
                                                    "
                                                >
                                                    <select
                                                        v-model="
                                                            formModal[item.name]
                                                        "
                                                        class="form-control"
                                                    >
                                                        <option value="">
                                                            -- Select --
                                                        </option>
                                                        <option value="1">
                                                            Yes
                                                        </option>
                                                        <option value="0">
                                                            No
                                                        </option>
                                                    </select>
                                                </template>
                                                <template
                                                    v-else-if="
                                                        item.type == 'select'
                                                    "
                                                >
                                                    <select
                                                        v-model="
                                                            formModal[item.name]
                                                        "
                                                        class="form-control form-control-sm"
                                                    >
                                                        <option
                                                            disabled
                                                            value=""
                                                        >
                                                            Please select one
                                                        </option>
                                                        <option
                                                            :value="tag.value"
                                                            v-for="(
                                                                tag, indexTag
                                                            ) in item.options"
                                                            :key="indexTag"
                                                        >
                                                            {{ tag.label }}
                                                        </option>
                                                    </select>
                                                </template>
                                                <template
                                                    v-else-if="
                                                        item.type == 'select_product'
                                                    "
                                                >
                                                <select v-model="formModal[item.name]" class="form-control form-control-sm">
                                                        <option disabled value="">Please select one</option>
                                                            <option
                                                            :value="product.value"
                                                            v-for="(product, index) in products"
                                                            :key="index"
                                                            >
                                                            {{ product.label }}
                                                            </option>
                                                    </select>
                                                </template>
                                                <template
                                                    v-else-if="
                                                        item.type ==
                                                        'social_media'
                                                    "
                                                >
                                                    <select
                                                        v-model="
                                                            formModal[item.name]
                                                        "
                                                        class="form-control"
                                                    >
                                                        <option
                                                            value="facebook"
                                                        >
                                                            Facebook
                                                        </option>
                                                        <option
                                                            value="instagram"
                                                        >
                                                            Instagram
                                                        </option>
                                                        <option value="twitter">
                                                            Twitter
                                                        </option>
                                                        <option value="youtube">
                                                            Youtube
                                                        </option>
                                                    </select>
                                                </template>
                                                <template
                                                    v-else-if="
                                                        item.type == 'number'
                                                    "
                                                >
                                                    <input
                                                        type="number"
                                                        v-model="
                                                            formModal[item.name]
                                                        "
                                                        class="form-control"
                                                    />
                                                </template>
                                                <template
                                                    v-else-if="
                                                        item.type == 'date'
                                                    "
                                                >
                                                    <input
                                                        type="date"
                                                        v-model="
                                                            formModal[item.name]
                                                        "
                                                        class="form-control"
                                                    />
                                                </template>
                                                <template
                                                    v-else-if="
                                                        item.type ==
                                                            'textarea' ||
                                                        item.type == 'editor' ||
                                                        item.type == 'editor_simple'
                                                    "
                                                >
                                                    <textarea
                                                        type="text"
                                                        v-model="
                                                            formModal[item.name]
                                                        "
                                                        :id="
                                                            aliascomponent +
                                                            '_' +
                                                            item.name
                                                        "
                                                        class="form-control"
                                                        maxlength="1000"
                                                    />
                                                    <div
                                                        class="form-text"
                                                        v-if="item.max"
                                                    >
                                                        {{
                                                            `Max ${item.max} Characters`
                                                        }}
                                                    </div>
                                                </template>
                                                <template
                                                    v-else-if="
                                                        item.type == 'url'
                                                    "
                                                >
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <div
                                                                class="input-prepend input-group"
                                                            >
                                                                <span
                                                                    class="input-group-text"
                                                                    >{{
                                                                        feUrl
                                                                    }}</span
                                                                >
                                                                <input
                                                                    type="text"
                                                                    v-model="
                                                                        formModal[
                                                                            item
                                                                                .name
                                                                        ]
                                                                    "
                                                                    class="form-control"
                                                                />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                                <template
                                                    v-else-if="
                                                        item.type == 'url2'
                                                    "
                                                >
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <div
                                                                class="input-prepend input-group"
                                                            >
                                                                <span
                                                                    class="input-group-text"
                                                                    >URL</span
                                                                >
                                                                <input
                                                                    type="url"
                                                                    v-model="
                                                                        formModal[
                                                                            item
                                                                                .name
                                                                        ]
                                                                    "
                                                                    class="form-control"
                                                                />
                                                            </div>
                                                            <p
                                                                class="help-block"
                                                            >
                                                                ex:
                                                                https://example.com
                                                            </p>
                                                        </div>
                                                    </div>
                                                </template>
                                                <template
                                                    v-else-if="
                                                        item.type == 'time_zone'
                                                    "
                                                >
                                                    <select
                                                        v-model="
                                                            formModal[item.name]
                                                        "
                                                        class="form-control"
                                                    >
                                                        <option value="WIB">
                                                            WIB
                                                        </option>
                                                        <option value="WITA">
                                                            WITA
                                                        </option>
                                                        <option value="WIT">
                                                            WIT
                                                        </option>
                                                    </select>
                                                </template>
                                                <template
                                                    v-else-if="
                                                        item.type == 'color'
                                                    "
                                                >
                                                    <select
                                                        v-model="
                                                            formModal[item.name]
                                                        "
                                                        class="form-control"
                                                    >
                                                        <option value="">
                                                            -- Color --
                                                        </option>
                                                        <option
                                                            v-for="item in colorPalette"
                                                            :value="item.color"
                                                        >
                                                            {{ item.title }}
                                                        </option>
                                                    </select>
                                                </template>

                                                <template
                                                    v-else-if="
                                                        item.type == 'upload'
                                                    "
                                                >
                                                    <template v-if="imageSrc">
                                                        <a
                                                            v-if="
                                                                formModal[
                                                                    item.name
                                                                ]
                                                            "
                                                            :href="
                                                                url +
                                                                '/' +
                                                                formModal[
                                                                    item.name
                                                                ]
                                                            "
                                                             target="_blank"
                                                        >Link</a>
                                                    </template>
                                                    <template v-else>
                                                        <a
                                                            v-if="
                                                                formModal[
                                                                    item.name
                                                                ]
                                                            "
                                                            :href="
                                                                url +
                                                                '/' +
                                                                formModal[
                                                                    item.name
                                                                ]
                                                            "
                                                             target="_blank"
                                                        >Link</a>
                                                    </template><br>
                                                    <button v-if="
                                                                formModal[
                                                                    item.name
                                                                ]
                                                            "
                                                        type="button"
                                                        v-on:click="
                                                            removeImage(
                                                                $event,
                                                                item.name
                                                            )
                                                        "
                                                        class="btn btn-danger mt-2"
                                                    >
                                                        Remove
                                                    </button>
                                                    <input
                                                        type="hidden"
                                                        v-model="
                                                            formModal[item.name]
                                                        "
                                                    />
                                                    <input
                                                        v-on:change="
                                                            changeImage(
                                                                $event,
                                                                item.name
                                                            )
                                                        "
                                                        type="file"
                                                        class="form-control"
                                                        accept="document/pdf, document/docs"
                                                    />
                                                    <small for="">{{
                                                        item.info
                                                    }}</small>
                                                </template>
                                                <template
                                                    v-else-if="
                                                        item.type == 'whatsapp'
                                                    "
                                                >
                                                    <select
                                                        v-model="
                                                            formModal[item.name]
                                                        "
                                                        class="form-control"
                                                    >
                                                        <option value="">
                                                            -- Whatsapp --
                                                        </option>
                                                        <option
                                                            v-for="item in whatsapp"
                                                            :value="item.id"
                                                        >
                                                            {{ item.contact }} -
                                                            {{
                                                                item.phone_number
                                                            }}
                                                        </option>
                                                    </select>
                                                </template>
                                                <template
                                                    v-else-if="
                                                        item.type == 'repeater'
                                                    "
                                                >
                                                <repeater-component2
                                                    :url="url"
                                                    :field="item"
                                                    :value="formModal[item.name]"
                                                    :name="item.name"
                                                    @change="updateRepeater2"
                                                    :aliascomponent="generateAliasComponent(item, aliascomponent)"
                                                    :editor_fields="getEditorFields(item.list)"
                                                    :editor_simple_fields="getEditorSimpleFields(item.list)"
                                                >
                                                </repeater-component2>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button
                                        type="button"
                                        class="btn btn-secondary"
                                        @click="showModal = false"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-primary"
                                        @click="
                                            modeEdit
                                                ? updateList()
                                                : submitList()
                                        "
                                    >
                                        {{ modeEdit ? "Update" : "Save" }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
export default {
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
      allowableTypes: ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'avi', 'mov', 'wmv','flv'],
      maximumSize: 26214400,
      allowableImageTypes: ['jpg', 'jpeg', 'webp','png'],
      allowableDocsTypes: ['docs','pdf'],
      maxImageSize:  500 * 1024,
      maxVideoSize: 35 * 1024 * 1024,
      selectedImage: null,
      size: null,
      imageSrc: '',
      whatsapp: [],
      colorPalette: [],
      allCkEditors: [],
      products: [],
      subsidiary_list : [],
    }
  },
  mounted() {
    this.fetchProducts()
    this.generateFormModal()
    // this.getColorPalette()
    // this.getWhatsappContact()
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
    generateAliasComponent(item, aliasComponent ) {
      item.list.forEach(field => {
        let fieldName = `${aliasComponent}[${field.name}]`;
        let aliasComponent2 = `${aliasComponent}_${item.name}_${field.name}`;
        
        return aliasComponent2;
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
      let editor_simple_fields = [];
      list.forEach(col => {
        if (col.type === 'editor_simple') {
            editor_simple_fields.push(col.name);
        }
      });
      return editor_simple_fields;
    },
    updateRepeater2(data) {
        if(this.subsidiary_list?.length){
            this.formModal.subsidiary_list = this.subsidiary_list
        }
      this.formModal[data.name] = data.value
      
    },
    getWhatsappContact() {
      axios.get('/antiadmin/get-whatsapp')
          .then((res) => {
            this.whatsapp = res.data
          })
    },
    getColorPalette() {
      axios.get('/antiadmin/get-color-palette')
          .then((res) => {
            this.colorPalette = res.data
          })
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
        this.editor_fields.forEach(editor_field => {
          ClassicEditor
                .create( document.querySelector('#' + this.aliascomponent + '_' + editor_field), {
                    ckfinder: {
                        uploadUrl: '/antiadmin/image-upload',
                    },
                    mediaEmbed: {
                        previewsInData:true
                    },
                    toolbar: [
                        'heading', '|', 'alignment',
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
                .then(editor => {
                    this.allCkEditors[editor_field] = editor;
                } )
                .catch( error => {
                    console.error( error );
                });
        });
        this.editor_simple_fields.forEach(editor_field => {
          ClassicEditor
                .create( document.querySelector('#' + this.aliascomponent + '_' + editor_field), {
                    ckfinder: {
                        uploadUrl: '/antiadmin/image-upload',
                    },
                    mediaEmbed: {
                        previewsInData:true
                    },
                    toolbar: [],
                })
                .then(editor => {
                    this.allCkEditors[editor_field] = editor;
                } )
                .catch( error => {
                    console.error( error );
                });
        });
      });
    },
    prepareDataEditor() {
      this.editor_fields.forEach(editor_field => {
        this.formModal[editor_field] = this.allCkEditors[editor_field].getData()
      })
      this.editor_simple_fields.forEach(editor_field => {
        this.formModal[editor_field] = this.allCkEditors[editor_field].getData()
      })
    },
    submitList() {
      this.prepareDataEditor()
      if(this.subsidiary_list?.length){
        this.formModal.subsidiary_list = this.subsidiary_list
      }
      this.lists.push(this.formModal)
      console.log(this.lists)
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
          if (this.lists[index] === list) {
            this.lists.splice(index, 1)
          } else {
            let found = this.lists.indexOf(list)
            this.lists.splice(found, 1)
          }
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
      this.formModal = list
      if(this.subsidiary_list?.length){
        this.formModal.subsidiary_list = this.subsidiary_list
      }
      console.log("form modal 1", this.formModal)
    },
    updateList() {
      this.prepareDataEditor()
      if(this.subsidiary_list?.length){
        this.formModal.subsidiary_list = this.subsidiary_list
      }
      this.lists[this.formIndex] = this.formModal
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
                alert(`Upload failed, please use a file under 15 MB.`);
                return false;
            }

      return true
    },
    onImageError(err) {
      console.log(err, 'do something with error')
    },
    changeImage($event, name, size=512) {
      this.selectedImage = $event.target.files[0]
      //validate the image
      if (!this.validate(this.selectedImage))
        return
      // create a form
      const form = new FormData();
      form.append('image', this.selectedImage);
      // submit the image
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
        console.log("bhjdebjj");
        let self = this
        self.formModal[name] = ''
        self.imageSrc = ''

    }
  }
}
</script>
