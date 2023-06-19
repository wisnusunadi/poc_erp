import Vue from 'vue'
import Index from './Index.vue'
import router from './routes'
import VueRouter from 'vue-router';
import Vuetify from 'vuetify'
import VueSweetalert2 from "vue-sweetalert2"
import Vuex from 'vuex';
import storeData from './store'
import 'vuetify/dist/vuetify.min.css'
import '@mdi/font/css/materialdesignicons.css'

window.Vue = require('vue').default;
Vue.use(VueRouter);
Vue.use(Vuetify)
Vue.use(VueSweetalert2);

const store = new Vuex.Store(storeData);

const app = new Vue({
    el: '#app',
    store,
    router,
    vuetify: new Vuetify(),
    components: {
        "index": Index
    }
}); 