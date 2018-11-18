
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Toastr notifier
 */
window.toastr = require('toastr');
window.toastr.options = {
    "timeOut": "5000"
};