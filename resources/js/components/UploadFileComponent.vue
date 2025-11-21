<template>
    <div class="form-group">
        <label class="col-form-label font-weight-bold">{{ field.label }}</label>
        <input type="hidden" v-bind:name="component" :value="JSON.stringify(valueFile)">
        <p>
          <span v-if="valueFile" >Url: </span>
          <a v-if="valueFile" :href="valueFileUrl" target="_blank">{{valueFileUrl}}</a>
          <a v-else-if="field.value" :href="fieldValueUrl" target="_blank">{{fieldValueUrl}}</a>
          <span v-else></span>

          <button v-if="valueFile" type="button" v-on:click="removeImage($event)"class="btn btn-danger mt-2">Remove </button><br>
        </p>
        <input
            type="file"
            class="filepond form-control"
            @change="changeImage"
            accept="application/pdf" />
        <small>{{field.info}}</small>
        <p v-if="loading">File is uploading...</p>
    </div>
</template>

<script>
export default {
    props: ['url', 'field', 'value', 'component'],
    data() {
        return {
            allowableTypes: ['pdf'],
            maximumSize: 35 * 1024 * 1024, // 2 MB in bytes
            selectedFile: null,
            valueFile: null,
            loading: false
        }
    },
    computed: {
      valueFileUrl() {
        return this.url + '/' + this.valueFile.path
      },
      fieldValueUrl() {
        return this.url + '/' + this.field.value.path
      }
    },
    mounted() {
        if(this.value) {
            this.valueFile = JSON.parse(this.value)
        }
    },

    methods: {
        removeImage($event, name) {
            this.valueFile = null;
        },
        validate(image) {
            if (!this.allowableTypes.includes(image.name.split(".").pop().toLowerCase())) {
                alert(`Sorry you can only upload ${this.allowableTypes.join("|").toUpperCase()} files.`)
                return false
            }

            if (image.size > this.maximumSize){
                alert("Upload failed, please use another pdf file under 15Mb. Please make sure all the data are filled")
                return false
            }

            return true
        },
        isFileExist(file) {
          const form = new FormData();
          form.append('filename', file.name);
          window.axios.post('/antiadmin/page/check-file-exist',form)
            .then(response => {
              const res = response.data
              return res.is_exists;
            })
            .catch(err => alert(err.response.data.message));
        },
        onImageError(err){
            console.log(err, 'do something with error')
        },
        changeImage($event) {
            this.selectedFile = $event.target.files[0];
            const extension = this.selectedFile.name.split('.').pop().toLowerCase();
            const isDocs = this.allowableTypes.includes(extension);

            if (isDocs && this.selectedFile.size > this.maximumSize) {
                alert(`Upload failed, please use a document under 15MB.`);
                return false;
            }

            //validate the image
            if (!this.validate(this.selectedFile))
                return

            this.loading = true;

            const formCheckIsExist = new FormData();
            formCheckIsExist.append('filename', this.selectedFile.name);
            // window.axios.post('/antiadmin/page/check-file-exist',formCheckIsExist)
            //   .then(response => {
            //       const res = response.data
            //       const isExist = res.is_exist;
            //       let confirm = !isExist;
            //       if (isExist) {
            //         if (window.confirm('File already exists, are you sure to overwrite?')) {
            //           confirm = true;
            //         }
            //       }

            //       if (confirm) {
                    // create a form
                    const form = new FormData();
                    form.append('image', this.selectedFile);
                    // submit the image
                    let self = this
                    window.axios.post('/antiadmin/page/image', form)
                    .then(response => {
                      const res = response.data
                      const fileObj = {
                          path: res.path,
                          size: res.size,
                          extension: res.extension,
                      };
                      self.valueFile = fileObj
                      this.loading = false;
                    })
                    .catch(err => {
                        console.log(err);
                        this.success = false;
                    })
                    // window.axios.post('/antiadmin/page/file',form)
                    //   .then(response => {
                    //     const res = response.data
                    //     const fileObj = {
                    //       path: res.path,
                    //       size: res.size,
                    //       extension: res.extension,
                    //     };
                    //     self.valueFile = fileObj
                    //   })
                    //   .catch(err => alert(err.response.data.message));
            //       }
            //   })
            // .catch(err => alert(err.response.data.message));

        }
    }
}
</script>
