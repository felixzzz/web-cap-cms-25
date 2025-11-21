<template>
    <div class="form-group">
        <label class="col-form-label font-weight-bold">{{ field.label }}</label>
        <input type="hidden" v-bind:name="component" :value="valueImage">
        <img :alt="field.label" v-if="valueImage" :src="url + '/' + valueImage" class="img-thumbnail d-flex" style="max-height: 300px;" />

    </div>
</template>

<script>
export default {
    props: ['url', 'field', 'value', 'component'],
    data() {
        return {
            allowableTypes: ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            maximumSize: 5000000,
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
    }
}
</script>
