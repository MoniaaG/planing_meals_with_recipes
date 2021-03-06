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
    .js('resources/js/delete/recipe.js', 'public/js/delete')
    .js('resources/js/delete/product_proposition_reject.js', 'public/js/delete')
    .js('resources/js/fullcalendar/main.js', 'public/fullcalendar')
    .js('resources/js/likes.js', 'public/js')
    .js('resources/js/assign_recipe.js', 'public/js')
    .js('resources/js/unsign_recipe.js', 'public/js')
    .js('resources/js/addProductFromList.js', 'public/js')
    .js('resources/js/product_proposition_accept.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/calendar.scss', 'public/css')
    .sass('resources/sass/homepage.scss', 'public/css')
    .sass('resources/sass/style.scss', 'public/css')
    .css('resources/css/fullcalendar/main.css', 'public/fullcalendar');

