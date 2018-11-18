
window._ = require('lodash');
window.Popper = require('popper.js').default;

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

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found');
}