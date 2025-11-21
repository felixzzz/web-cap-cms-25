<template>
    <div class="form-group">
        <label class="col-form-label font-weight-bold">{{ field.label }}</label>
        <input type="hidden" v-bind:name="component" :value="JSON.stringify(valueFile)">
        <p>
          <span>Url: </span>
          <a v-if="valueFile" :href="valueFileUrl" target="_blank">{{valueFileUrl}}</a>
          <a v-else-if="field.value" :href="fieldValueUrl" target="_blank">{{fieldValueUrl}}</a>
          <span v-else></span>
        </p>
        <input
            type="file"
            class="filepond form-control"
            @change="changeImage"
            accept="application/pdf" />
      <small>{{field.info}}</small>
    </div>
</template>

<script>
export default {
    props: ['url', 'field', 'value', 'component'],
    data() {
        return {
            allowableTypes: ['pdf'],
            maximumSize: 30000000,
            selectedFile: null,
            valueFile: null
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
        validate(image) {
            if (!this.allowableTypes.includes(image.name.split(".").pop().toLowerCase())) {
                alert(`Sorry you can only upload ${this.allowableTypes.join("|").toUpperCase()} files.`)
                return false
            }

            if (image.size > this.maximumSize){
                alert("Upload failed, please use another pdf file under 30Mb. Please make sure all the data are filled")
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

            //validate the image
            if (!this.validate(this.selectedFile))
                return

            const formCheckIsExist = new FormData();
            formCheckIsExist.append('filename', this.selectedFile.name);
            window.axios.post('/antiadmin/page/check-file-exist',formCheckIsExist)
              .then(response => {
                  const res = response.data
                  const isExist = res.is_exist;
                  let confirm = !isExist;
                  if (isExist) {
                    if (window.confirm('File already exists, are you sure to overwrite?')) {
                      confirm = true;
                    }
                  }

                  if (confirm) {
                    // create a form
                    const form = new FormData();
                    form.append('file', this.selectedFile);
                    form.append('existing_file', this.valueFile?.path);
                    // submit the image
                    let self = this
                    window.axios.post('/antiadmin/page/file',form)
                      .then(response => {
                        const res = response.data
                        const fileObj = {
                          path: res.path,
                          size: res.size,
                          extension: res.extension,
                        };
                        self.valueFile = fileObj
                      })
                      .catch(err => alert(err.response.data.message));
                  }
              })
            .catch(err => alert(err.response.data.message));

        }
    }
}
</script>
