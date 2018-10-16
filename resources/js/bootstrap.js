
window._ = require('lodash');
window.Popper = require('popper.js').default;

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');
    require('bootstrap/dist/js/bootstrap.min');

    require('fastclick/lib/fastclick');
    require('chart.js/dist/Chart.min');

    require('jquery-sparkline/jquery.sparkline.min');
    require('icheck/icheck.min');

    ///......///

    require('flot.curvedlines/curvedLines');
    require('flot-spline');
    require('flot-orderbars/js/jquery.flot.orderBars');

    require('datejs');

    require('bootstrap-daterangepicker/daterangepicker');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found');
}