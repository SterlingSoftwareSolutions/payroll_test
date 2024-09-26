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

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css');




//add jquery files

    // const mix = require('laravel-mix');

    // mix.js('resources/js/app.js', 'public/js')
    //    .sass('resources/sass/app.scss', 'public/css')
    //    .scripts([
    //        'node_modules/jquery/dist/jquery.min.js',
    //        // Add other script dependencies here
    //    ], 'public/js/all.js');
    