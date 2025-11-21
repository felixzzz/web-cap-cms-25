<template>
    <div class="form-group">
        <label class="col-form-label font-weight-bold">{{ field.label }}</label>
        <input type="hidden" v-bind:name="component" :value="valueImage">
        <img :alt="field.label" v-if="valueImage" :src="url + '/' + valueImage" class="img-thumbnail d-flex" style="max-height: 300px;" />
        <input
            type="file"
            class="filepond form-control"
            @change="changeImage"
            accept="image/png, image/jpeg" />
      <small>{{field.info}}</small>
    </div>
</template>

<script>
export default {
    props: ['url', 'field', 'value', 'component'],
    data() {
        return {
            allowableTypes: ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            maximumSize: 800000,
            selectedImage: null,
            imageSrc: null,
            valueImage: null
        }
    },
    mounted() {
        if(this.value) {
            this.valueImage = this.value
        }
    },

    methods: {
        validate(image) {
            if (!this.allowableTypes.includes(image.name.split(".").pop().toLowerCase())) {
                alert(`Sorry you can only upload ${this.allowableTypes.join("|").toUpperCase()} files.`)
                return false
            }

            if (image.size > this.maximumSize){
                alert("Upload failed, please use another image file under 800kb. Please make sure all the data are filled")
                return false
            }

            return true
        },
        onImageError(err){
            console.log(err, 'do something with error')
        },
        changeImage($event) {
            this.selectedImage = $event.target.files[0]
            //validate the image
            if (!this.validate(this.selectedImage))
                return
            // create a form
            const form = new FormData();
            form.append('image', this.selectedImage);
            // submit the image
            let self = this
            window.axios.post('/antiadmin/page/image',form)
                .then(response => {
                    const res = response.data
                    self.valueImage = res.path
                    self.imageSrc = res.full_path
                })
                .catch(err => console.log(err));
        }
    }
}
</script>
