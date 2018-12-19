
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./projects');

window.useMock = false;

/**
 * Toastr notifier
 */
window.toastr = require('toastr');
window.toastr.options = {
    "timeOut": "5000"
};

import ProjectConfiguration from './components/ProjectConfigurationComponent'

Vue.config.productionTip = false;

const app = new Vue({
    components: {
        'project-configuration': ProjectConfiguration
    }
}).$mount('#app');