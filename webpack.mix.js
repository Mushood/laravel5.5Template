let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
    .js('resources/assets/js/admin.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .sass('resources/assets/sass/admin.scss', 'public/css');

mix.copy('resources/assets/css/bootstrap.min.css', 'public/css/bootstrap.min.css');
mix.copy('resources/assets/css/AdminLTE.min.css', 'public/css/AdminLTE.min.css');
mix.copy('resources/assets/css/skin-purple.min.css', 'public/css/skin-purple.min.css');
mix.copy('resources/assets/css/pace.min.css', 'public/css/pace.min.css');
mix.copy('resources/assets/css/backpack.base.css', 'public/css/backpack.base.css');
mix.copy('resources/assets/css/pnotify.custom.min.css', 'public/css/pnotify.custom.min.css');

mix.copy('resources/assets/js/bootstrap.min.js', 'public/js/bootstrap.min.js');
mix.copy('resources/assets/js/pace.min.js', 'public/js/pace.min.js');
mix.copy('resources/assets/js/jquery.slimscroll.min.js', 'public/js/jquery.slimscroll.min.js');
mix.copy('resources/assets/js/fastclick.js', 'public/js/fastclick.js');
mix.copy('resources/assets/js/backpackapp.min.js', 'public/js/backpackapp.min.js');
mix.copy('resources/assets/js/jquery-3.1.1.min.js', 'public/js/jquery-3.1.1.min.js');
