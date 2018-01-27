require('./bootstrap');
import VueSweetalert2 from 'vue-sweetalert2';
import Slick from 'vue-slick';

window.Vue = require('vue');
Vue.use(VueSweetalert2);
Vue.component('multiplefileuploader', require('./components/fileuploader.vue'));

window.Event = new Vue();

const app = new Vue({
    el: '#app',

    components: { Slick },

    data:{
        slickOptions: {
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 4000,
            arrows : true,
            nextArrow: '<i class="fa fa-arrow-right"></i>',
            prevArrow: '<i class="fa fa-arrow-left"></i>',
        },
    },
});
