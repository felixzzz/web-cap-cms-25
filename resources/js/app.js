/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

// Disable since its causes error
// require('./bootstrap');

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Require Vue
window.Vue = require("vue").default;
// Register Vue Components
Vue.component(
    "example-component",
    require("./components/ExampleComponent.vue").default
);
// Vue.component('meta-component', require('./components/MetaComponent.vue').default);
Vue.component(
    "image-component",
    require("./components/ImageComponent.vue").default
);
Vue.component(
    "upload-component",
    require("./components/UploadFileComponent.vue").default
);
Vue.component(
    "video-component",
    require("./components/UploadVideoComponent.vue").default
);
Vue.component(
    "image-show-component",
    require("./components/ImageShowComponent.vue").default
);
Vue.component(
    "repeater-page-component",
    require("./components/RepeaterPageComponent.vue").default
);
Vue.component(
    "repeater-en-component",
    require("./components/RepeaterEnComponent.vue").default
);
Vue.component(
    "repeater-component",
    require("./components/RepeaterComponent.vue").default
);
Vue.component(
    "repeater-component2",
    require("./components/RepeaterComponent2.vue").default
);
Vue.component(
    "repeater-show-component",
    require("./components/RepeaterShowComponent.vue").default
);

// Initialize Vue
const appElement = document.getElementById("app");
if (appElement) {
    const app = new Vue({
        el: "#app",
    });
}
