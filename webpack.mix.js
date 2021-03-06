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

// mix.js('resources/js/app.js', 'public/js')
   // .sass('resources/sass/app.scss', 'public/css');

mix.js('resources/js/dashboards/black/src/main.js', 'public/js')
mix.js('resources/js/login/login.js', 'public/js')

// mix.js('resources/js/dashboards/vue-material/src/main.js', 'public/js')
   // .sass('resources/sass/app.scss', 'public/css');
