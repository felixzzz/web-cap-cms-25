<template>
    <div class="card repeater">
        <div class="card-header">
            {{ field.label }}
            <input
                type="hidden"
                v-bind:name="component"
                :value="JSON.stringify(lists)"
            />
            <div class="card-header-actions"></div>
        </div>
        <div class="card-body">
            <div
                class="row align-items-center pt-1"
                v-for="(list, no) in lists"
                :key="no"
            >
                <div class="col">
                    <table class="table table-sm table-bordered table-fit">
                        <tbody>
                            <tr v-for="(item, i) in field.list" :key="i">
                                <th width="20%">{{ item.label }}</th>
                                <template
                                    v-if="
                                        item.type == 'image' && list[item.name]
                                    "
                                >
                                    <td>
                                        <img
                                            :src="url + '/' + list[item.name]"
                                            class="img-thumbnail d-flex"
                                            style="max-height: 300px"
                                        />
                                    </td>
                                </template>
                                <template
                                    v-else-if="
                                        item.type == 'color' && list[item.name]
                                    "
                                >
                                    <td>
                                        <input
                                            type="color"
                                            class="form-control"
                                            :value="list[item.name]"
                                            disabled
                                        />
                                    </td>
                                </template>
                                <template
                                    v-else-if="
                                        item.type == 'repeater' &&
                                        list[item.name]
                                    "
                                >
                                    <div
                                        class="row align-items-center pt-1 ml-1 mr-1"
                                        v-for="(list2, no) in list[item.name]"
                                        :key="no"
                                    >
                                        <div class="col">
                                            <table
                                                class="table table-sm table-bordered table-fit"
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
                                                                    'image' &&
                                                                list2[
                                                                    item2.name
                                                                ]
                                                            "
                                                        >
                                                            <td>
                                                                <img
                                                                    :src="
                                                                        url +
                                                                        '/' +
                                                                        list2[
                                                                            item2
                                                                                .name
                                                                        ]
                                                                    "
                                                                    class="img-thumbnail d-flex"
                                                                    style="
                                                                        max-height: 100px;
                                                                    "
                                                                />
                                                            </td>
                                                        </template>
                                                        <template
                                                            v-else-if="
                                                                item2.type ==
                                                                    'color' &&
                                                                list2[
                                                                    item2.name
                                                                ]
                                                            "
                                                        >
                                                            <td>
                                                                <input
                                                                    type="color"
                                                                    class="form-control"
                                                                    :value="
                                                                        list2[
                                                                            item2
                                                                                .name
                                                                        ]
                                                                    "
                                                                    disabled
                                                                />
                                                            </td>
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
                                    <td
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
                <div class="col-1"></div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        "url",
        "field",
        "value",
        "component",
        "aliascomponent",
        "editor_fields",
    ],
    data() {
        return {
            feUrl: '',
            showModal: false,
            formModal: {},
            lists: [],
            modeEdit: false,
            formIndex: "",
            allowableTypes: ["jpg", "jpeg", "png", "gif"],
            maximumSize: 5000000,
            selectedImage: null,
            imageSrc: "",
        };
    },
    mounted() {
        if (this.value) {
            this.lists = JSON.parse(this.value);
        }
    },

    methods: {},
};
</script>
