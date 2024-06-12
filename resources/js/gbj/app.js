import Vue from "vue";
import Index from "./Index.vue";
import router from "./routes";
import VueRouter from "vue-router";
import VueSweetalert2 from "vue-sweetalert2";
import Vuex from "vuex";
import vSelect from "vue-select";
import dateFormat from "./plugins/dateFormat";
import numberOnly from "./plugins/numberOnly";
import dateTimeFormat from "./plugins/dateTimeFormat";
import storeData from "./store";
import DataTable from "./components/DataTable.vue";
import "vue-select/dist/vue-select.css";
import axiosPlugin from "./plugins/axiosPlugin";

window.Vue = Vue;
Vue.use(VueRouter);
Vue.use(VueSweetalert2);
Vue.use(Vuex);
Vue.component("v-select", vSelect);
Vue.component("data-table", DataTable);
Vue.use(dateFormat);
Vue.use(dateTimeFormat);
Vue.use(numberOnly);
Vue.use(axiosPlugin);

const store = new Vuex.Store(storeData);

const app = new Vue({
    el: "#app",
    router,
    store,
    components: {
        index: Index,
    },
});
