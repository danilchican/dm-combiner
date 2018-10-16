const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

if (mix.config.hmr) {
    mix.webpackConfig({
        output: {
            chunkFilename: '[name].js',
        }
    });
} else {
    mix.webpackConfig({
        output: {
            publicPath: '/',
            chunkFilename: '[name].[chunkhash].js',
        }
    });
}

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/login.scss', 'public/css')
    .sass('resources/sass/dashboard.scss', 'public/css');
