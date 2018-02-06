require('./bootstrap');
import VueSweetalert2 from 'vue-sweetalert2';
import { VueEditor } from 'vue2-editor'
import VeeValidate from 'vee-validate';
import moment from 'vue-moment';

window.Vue = require('vue');
Vue.use(VueSweetalert2);
Vue.use(VueEditor);
Vue.use(moment);

const config_validation = {
    errorBagName: 'errors', // change if property conflicts
    fieldsBagName: 'fields',
    delay: 0,
    locale: 'en',
    dictionary: null,
    strict: true,
    classes: false,
    classNames: {
        touched: 'touched', // the control has been blurred
        untouched: 'untouched', // the control hasn't been blurred
        valid: 'valid', // model is valid
        invalid: 'invalid', // model is invalid
        pristine: 'pristine', // control has not been interacted with
        dirty: 'dirty' // control has been interacted with
    },
    events: 'input|blur',
    inject: true,
    validity: false,
    aria: true,
    i18n: null, // the vue-i18n plugin instance,
    i18nRootKey: 'validations' // the nested key under which the validation messsages will be located
};
Vue.use(VeeValidate, config_validation);

window.Event = new Vue();

Vue.component('multiplefileuploader', require('./components/fileuploader.vue'));


const app = new Vue({
    el: '#admin',

    data:{
    },
});