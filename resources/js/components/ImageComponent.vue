<template>
    <div class="form-group">
        <label class="col-form-label font-weight-bold">{{ field.label }} </label>
        <input type="hidden" :name="component" :value="valueImage" />
        
        <!-- Display image and remove button -->
        <div v-if="valueImage" style="margin-bottom:10px">
            <img
                :alt="field.label"
                :src="url + '/' + valueImage"
                class="img-thumbnail d-flex"
                style="max-height: 300px"
            />
            <button
                type="button"
                @click.prevent="removeImage"
                class="btn btn-danger mt-2"
            >
                Remove
            </button><br>
        </div>
        
        <!-- File input for image upload -->
        <input
            type="file"
            class="filepond form-control"
            @change="changeImage2"
            accept="image/png, image/jpeg"
        />
        <small><i>{{ field.info }}</i></small>
    </div>
</template>
<script>
export default {
    props: {
        url: String,
        field: Object,
        value: String,
        component: String,
        masterlang: String
    },
    data() {
        return {
            allowableTypes: ["jpg", "jpeg", "png", "gif", "webp"],
            maximumSize: 500 * 1024,
            selectedImage: null,
            imageSrc: null,
            valueImage: this.value || null,
        };
    },
    methods: {
        validate(image) {
            if (
                !this.allowableTypes.includes(
                    image.name.split(".").pop().toLowerCase()
                )
            ) {
                alert(
                    `Sorry, you can only upload ${this.allowableTypes
                        .join("|")
                        .toUpperCase()} files.`
                );
                return false;
            }

            if (image.size > this.maximumSize) {
                alert(
                        "Upload failed, please use another image file under 400KB. Please make sure all the data are filled."
                    );
                return false;
            }

            return true;
        },
        onImageError(err) {
            console.log(err, "do something with error");
        },
        changeImage2($event) {
            this.selectedImage = $event.target.files[0];
            // Validate the image
            if (!this.validate(this.selectedImage)) return;
            // Create a form
            const form = new FormData();
            form.append("image", this.selectedImage);
            // Submit the image
            window.axios
                .post("/antiadmin/page/image", form)
                .then((response) => {
                    const res = response.data;
                    this.valueImage = res.path;
                    this.imageSrc = res.full_path;
                })
                .catch((err) => console.log(err));
        },
        removeImage() {
            event.preventDefault();
            this.valueImage = null;
            this.imageSrc = null;
        },
    },
};
</script>

