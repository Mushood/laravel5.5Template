require('./bootstrap');
import VueSweetalert2 from 'vue-sweetalert2';
import { VueEditor } from 'vue2-editor'

window.Vue = require('vue');
Vue.use(VueSweetalert2);
Vue.use(VueEditor);

window.Event = new Vue();

Vue.component('multiplefileuploader', require('./components/fileuploader.vue'));

const app = new Vue({
    el: '#admin',

    data:{
    },
});