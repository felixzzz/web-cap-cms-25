<template>
    <div class="card card-custom card-bordered repeater mt-6">
        <div class="card-header">
            <div class="card-title">{{ field.label }}</div>
            <input type="hidden" v-bind:name="component" :value="JSON.stringify(lists)" />
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-icon btn-primary" @click="addList">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row align-items-center" v-for="(list, no) in lists" :key="no">
                <div class="col mt-4">
                    <table class="table border table-row-bordered gy-2 gs-2 table-fit" :aria-label="field.label + '-table'">
                        <tbody>
                            <tr v-for="(item, i) in field.list" :key="i">
                                <th width="20%" class="border-end">{{ item.label }}</th>
                                <td>
                                    <template v-if="item.type == 'text'">
                                        <input type="text" v-model="list[item.name]" class="form-control" />
                                    </template>
                                    <template v-else-if="item.type == 'number'">
                                        <input type="number" v-model="list[item.name]" class="form-control" />
                                    </template>
                                    <template v-else-if="item.type == 'image' && list[item.name]">
                                        <img :src="url + '/' + list[item.name]" class="img-thumbnail d-flex" style="max-height: 300px" />
                                    </template>
                                    <template v-else-if="item.type == 'upload'">
                                        <template v-if="list[item.name]">
                                            <a :href="url + '/' + list[item.name]" target="_blank">Link</a>
                                            <br>
                                            <button type="button" @click="removeImage(no, item.name)" class="btn btn-danger mt-2">Remove</button>
                                            <br> <br>
                                        </template>
                                        <input type="hidden" v-model="list[item.name]" />
                                        <input type="file" class="form-control" accept="document/pdf, document/docs" @change="changeImage($event, no, item.name)" />
                                        <small>{{ item.info }}</small>
                                    </template>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-1">
                    <button type="button" class="btn btn-sm btn-danger btn-icon mt-1" @click="removeList(no)">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
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
        'locale',
    ],
    data() {
        return {
            lists: this.value ? JSON.parse(this.value) : [],
            allowableImageTypes: ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            allowableDocsTypes: ['pdf', 'doc', 'docs'],
            maxImageSize: 500 * 1024, // 500 KB
            maxDocSize: 15 * 1024 * 1024, // 15 MB
        }
    },
    methods: {
        addList() {
            const newItem = {};
            this.field.list.forEach(item => {
                newItem[item.name] = '';
            });
            this.lists.push(newItem);
        },
        removeList(index) {
            this.lists.splice(index, 1);
        },
        validate(file) {
            const extension = file.name.split('.').pop().toLowerCase();
            const isImage = this.allowableImageTypes.includes(extension);
            const isDocs = this.allowableDocsTypes.includes(extension);

            if (!isImage && !isDocs) {
                alert(`Sorry, you can only upload ${this.allowableImageTypes.join(', ').toUpperCase()} images or ${this.allowableDocsTypes.join(', ').toUpperCase()} documents.`);
                return false;
            }
            if (isImage && file.size > this.maxImageSize) {
                alert('Upload failed, please use an image under 500 KB.');
                return false;
            }
            if (isDocs && file.size > this.maxDocSize) {
                alert('Upload failed, please use a document under 15 MB.');
                return false;
            }
            return true;
        },
        changeImage(event, listIndex, itemName) {
            const file = event.target.files[0];
            if (!file || !this.validate(file)) return;

            const formData = new FormData();
            formData.append('image', file);

            axios.post('/antiadmin/page/image', formData)
                .then(response => {
                    this.lists[listIndex][itemName] = response.data.path; // Update the specific list item
                })
                .catch(err => console.log(err));
        },
        removeImage(listIndex, itemName) {
            this.lists[listIndex][itemName] = '';
        }
    },
}
</script>

<style>
/* Add your custom styles here */
</style>
