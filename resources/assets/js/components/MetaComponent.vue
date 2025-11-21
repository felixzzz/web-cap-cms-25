<template>
    <div>
        <template v-if="components">
            <div class="card" v-for="(component, i) in components" :key="i">
                <input type="text" v-bind:name="component.keyName" :value="JSON.stringify(metas[i])">
                <div class="card-header">
                    {{ component.label }}
                </div>
                <div class="card-body">
                    <div class="form-group row" v-for="(field, index) in component.fields" :key="index">
                        <label for="meta" class="col-md-2 col-form-label">{{ field.label }}</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" v-model="metas[i][index].value"  maxlength="100" required/>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
export default {
    props: ['components'],
    data() {
        return {
            message: 'Hello Vue!',
            metas: []
        }
    },
    mounted() {
        // console.log('Meta component mounted.')
        // console.log(this.message)
        // console.log(this.components)

        this.components.forEach(component => {
            console.log(component.keyName)
            var lists = []
            component.fields.forEach(field => {
                let list = {
                    section: component.keyName,
                    key: field.name,
                    value: '',
                    type: field.type
                }

                lists.push(list)
            });

            this.metas.push(lists)

            console.log(this.metas)
        });

    },

    methods: {

    }
}
</script>
