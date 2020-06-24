/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");
// require("./quillpro/scripts")

window.Vue = require("vue");

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import "vue-search-select/dist/VueSearchSelect.css";
import Vue from "vue";
import BootstrapVue from "bootstrap-vue";
import VueAuth from "@websanova/vue-auth";
import VueAxios from "vue-axios";
import auth from "./auth";
import App from "./components/App.vue";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import JsonExcel from "vue-json-excel";
import { store } from "./store";
import VModal from "vue-js-modal";
import VueCharts from "vue-chartjs";
import { Bar, Line } from "vue-chartjs";
import VueAlertify from "vue-alertify";
import router from "./router";

Vue.directive("focus", {
    // When the bound element is inserted into the DOM...
    inserted: function(el) {
        // Focus the element
        el.focus();
    }
});

// Vue.component('loading',Loading);
Vue.component("example", require("./components/ExampleComponent.vue"));
// Vue.component('v-app', require('./components/App.vue'));
Vue.component("loading", Loading);
Vue.component("App", require("./components/App.vue"));
Vue.component("downloadExcel", JsonExcel);

// Set Vue globally
window.Vue = Vue;
// Set Vue router
Vue.router = router;
axios.defaults.baseURL = "/api";
// Vue.use(VueRouter)
// Set Vue authentication
Vue.use(VModal);
Vue.use(BootstrapVue);
Vue.use(VueAxios, axios);
Vue.use(VueAuth, auth);
Vue.use(VueAlertify, {
    // dialogs defaults
    autoReset: true,
    basic: false,
    closable: true,
    closableByDimmer: true,
    frameless: false,
    maintainFocus: true, // <== global default not per instance, applies to all dialogs
    maximizable: true,
    modal: true,
    movable: true,
    moveBounded: false,
    overflow: true,
    padding: true,
    pinnable: true,
    pinned: true,
    preventBodyShift: false, // <== global default not per instance, applies to all dialogs
    resizable: true,
    startMaximized: false,
    transition: "pulse",

    // notifier defaults
    notifier: {
        // auto-dismiss wait time (in seconds)
        delay: 2,
        // default position
        position: "top-right",
        // adds a close button to notifier messages
        closeButton: true
    },

    // language resources
    glossary: {
        // dialogs default title
        title: "AlertifyJS",
        // ok button text
        ok: "OK",
        // cancel button text
        cancel: "Cancel"
    },

    // theme settings
    theme: {
        // class name attached to prompt dialog input textbox.
        input: "ajs-input",
        // class name attached to ok button
        ok: "ajs-ok",
        // class name attached to cancel button
        cancel: "ajs-cancel"
    }
});

new Vue({
    el: "#app",
    router,
    // store,
    template: "<App/>",
    components: {
        App
    }
});

// /**
//  * First we will load all of this project's JavaScript dependencies which
//  * includes Vue and other libraries. It is a great starting point when
//  * building robust, powerful web applications using Vue and Laravel.
//  */
// // require("jquery/dist/jquery.min.js");
// require("popper.js/dist/umd/popper.min.js");
// require("bootstrap/dist/js/bootstrap.min.js");

// require("./bootstrap");
// // require('./vendor/pace-progress/pace.js');
// require("perfect-scrollbar/dist/perfect-scrollbar.min.js");
// require("@coreui/coreui/dist/js/coreui.min.js");
// require("chart.js/dist/Chart.min.js");
// require("@coreui/coreui-plugin-chartjs-custom-tooltips/dist/js/custom-tooltips.min.js");
// window.Vue = require('vue');

// // require('./main');
// // import Vuetify from 'vuetify/lib'
// import VueRouter from 'vue-router';
// // Vue.use(require('vue-resource'));
// // Import component
// // import Loading from "vue-loading-overlay";
// // Import stylesheet
// // import "vue-loading-overlay/dist/vue-loading.css";
// import Dashboard from './components/Dashboard.vue';
// import App from './components/App.vue';

// import Welcome from './components/Welcome.vue';
// import UserIndex from './components/user/UserIndex.vue';
// import UserProfile from './components/user/UserProfile.vue';
// import ReportIndex from './components/report/ReportIndex.vue';
// import Vue from 'vue';
// import { store } from './store'
// // Vue.component('loading',Loading);
// Vue.component('example', require('./components/ExampleComponent.vue'));
// // Vue.component('v-app', require('./components/App.vue'));

// window.Vue.use(VueRouter);

// const routes = [
//     {
//         path: '/',
//         components: {
//             dashboard: Dashboard
//         }
//     },
//     { path: '/users', component: UserIndex, name: 'userIndex' }
//     , { path: '/reports', component: ReportIndex, name: 'reportIndex' }
//     , { path: '/welcome', component: Welcome, name: 'welcome' }
//     , { path: '/users/profile/:email', component: UserProfile, name: 'userProfile' }
// ]

// const router = new VueRouter({ routes })

// // const app = new Vue({
// //     router }).$mount('#app')

// new Vue({
//     el: '#app',
//     router,
//     store,
//     components: { App },
//     template: '<App/>'
// })
