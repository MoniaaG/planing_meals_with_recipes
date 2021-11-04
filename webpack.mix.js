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
    .js('resources/js/calendar.js', 'public/js')
    .js('resources/js/dashboard.js', 'public/js')
    .js('resources/js/delete/calendar_recipe.js', 'public/js/delete')
    .js('resources/js/delete/product_category.js', 'public/js/delete')
    .js('resources/js/delete/recipe_category.js', 'public/js/delete')
    .js('resources/js/delete/pantry_product.js', 'public/js/delete')
    .js('resources/js/fullcalendar/main.js', 'public/fullcalendar')
    .vue()
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/calendar.scss', 'public/css')
    .sass('resources/sass/homepage.scss', 'public/css')
    .css('resources/css/fullcalendar/main.css', 'public/fullcalendar');

