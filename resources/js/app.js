
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.jwt_decode = require('jwt-decode');

require('vue-resource');

/**
 * Toastr notifier
 */
window.toastr = require('toastr');
window.toastr.options = {
    "timeOut": "5000"
};

import router from './config/router'
import {store} from './store'
import NProgress from 'vue-nprogress'

Vue.config.productionTip = false;

Vue.use(NProgress);

const nprogress = new NProgress({parent: 'body'});

const app = new Vue({
    nprogress,
    router,
    store
}).$mount('#app');