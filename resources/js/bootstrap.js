window._ = require('lodash');
window.Popper = require('popper.js').default;

try {
    window.$ = window.jQuery = require('jquery');
    window.Papa = require('papaparse');

    require('bootstrap/dist/js/bootstrap.min');

    require('fastclick/lib/fastclick');
    require('chart.js/dist/Chart.min');

    require('jquery-sparkline/jquery.sparkline.min');
    require('icheck/icheck.min');

    require('flot.curvedlines/curvedLines');
    require('flot-spline');
    require('flot-orderbars/js/jquery.flot.orderBars');

    require('datejs');

    require('bootstrap-daterangepicker/daterangepicker');
} catch (e) {
}

const token = $('meta[name="csrf-token"]');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': token.attr('content')
    }
});

String.prototype.format = function() {
    var str = this;

    for (var i = 0; i < arguments.length; i++) {
        var reg = new RegExp("\\{" + i + "\\}", "gm");
        str = str.replace(reg, arguments[i]);
    }

    return str;
};