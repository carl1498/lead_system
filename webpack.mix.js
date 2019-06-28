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
   .js('resources/js/pages/book_management.js', 'public/js/pages')
   .js('resources/js/pages/dashboard.js', 'public/js/pages')
   .js('resources/js/pages/employees.js', 'public/js/pages')
   .js('resources/js/pages/invoice.js', 'public/js/pages')
   .js('resources/js/pages/student_add_history.js', 'public/js/pages')
   .js('resources/js/pages/student_delete_history.js', 'public/js/pages')
   .js('resources/js/pages/student_edit_history.js', 'public/js/pages')
   .js('resources/js/pages/student_settings.js', 'public/js/pages')
   .js('resources/js/pages/students.js', 'public/js/pages')
   .js('resources/js/pages/student_class.js', 'public/js/pages')
   .sass('resources/sass/app.scss', 'public/css')
   .version();
