<template>
    <div class="form-group">
        <label class="col-form-label font-weight-bold">{{ field.label }}</label>
        <input type="hidden" v-bind:name="component" :value="JSON.stringify(valueFile)">
        <p>
          <span v-if="valueFile">Url: </span>
          <a v-if="valueFile" :href="valueFileUrl" target="_blank">{{ valueFileUrl }}</a>
          <a v-else-if="field.value" :href="fieldValueUrl" target="_blank">{{ fieldValueUrl }}</a>
          <span v-else></span>
          <button v-if="valueFile" type="button" v-on:click="removeImage($event)" class="btn btn-danger mt-2">Remove</button><br>
        </p>
        <input
            type="file"
            class="filepond form-control"
            @change="changeVideo"
            accept="video/*" />
        <small>{{ field.info }}</small>
        <p v-if="loading">File is uploading...</p>
        <p v-if="success">File uploaded successfully!</p>
    </div>
</template>

<script>
export default {
    props: ['url', 'field', 'value', 'component'],
    data() {
        return {
            allowableTypes: ['mp4', 'avi', 'mov', 'mkv', 'webm'], // Add video formats here
            maximumSize: 5 * 1024 * 1024, // 5 MB in bytes
            selectedFile: null,
            valueFile: null,
            loading: false,
            success: false
        }
    },
    computed: {
        valueFileUrl() {
            return this.url + '/' + this.valueFile.path;
        },
        fieldValueUrl() {
            return this.url + '/' + this.field.value.path;
        }
    },
    mounted() {
        if (this.value) {
            this.valueFile = JSON.parse(this.value);
        }
    },
    methods: {
        removeImage($event) {
            this.valueFile = null;
        },
        validate(video) {
            const fileExtension = video.name.split(".").pop().toLowerCase();
            
            if (!this.allowableTypes.includes(fileExtension)) {
                alert(`Sorry, you can only upload ${this.allowableTypes.join(", ").toUpperCase()} files.`);
                return false;
            }

            if (video.size > this.maximumSize) {
                alert("Upload failed, please use another video file under 5 MB.");
                return false;
            }

            return true;
        },
        changeVideo($event) {
            this.selectedFile = $event.target.files[0];
            const extension = this.selectedFile.name.split('.').pop().toLowerCase();
            const isDocs = this.allowableTypes.includes(extension);

            if (isDocs && this.selectedFile.size > this.maximumSize) {
                alert(`Upload failed, please use a document under 5MB.`);
                return false;
            }

            // Validate the video
            if (!this.validate(this.selectedFile)) {
                return;
            }

            // Set loading state to true
            this.loading = true;
            this.success = false;

            // Create a form
            const form = new FormData();
            form.append('image', this.selectedFile);

            // Submit the video
            window.axios.post('/antiadmin/page/image', form)
                .then(response => {
                    const res = response.data;
                    const fileObj = {
                        path: res.path,
                        size: res.size,
                        extension: res.extension,
                    };
                    this.valueFile = fileObj;
                    this.loading = false;
                    this.success = true;
                })
                .catch(err => {
                    console.log(err);
                    this.success = false;
                })
                .finally(() => {
                    this.loading = false;
                })
        }
    }
}
</script>
