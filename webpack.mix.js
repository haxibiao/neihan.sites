let mix = require("laravel-mix");
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
mix.js("resources/assets/js/app.js", "public/js").sass("resources/assets/sass/app.scss", "public/css");
mix.copy("node_modules/summernote/dist/summernote.css", "public/css");
mix.copy("node_modules/summernote/dist/font/*", "public/css/font");
mix.styles(
    ["public/css/app.css", "public/css/site.css","public/fonts/iconfont.css"], "public/css/site.css").version();
mix.styles(
    ["public/css/app.css", "public/css/summernote.css", "public/css/site.css","public/fonts/iconfont.css"], "public/css/all.css").version();
mix.copy("node_modules/summernote/dist/summernote.js", "public/js");
mix.js("resources/assets/js/editor.js", "public/js")
mix.scripts(
    ["public/js/editor.js", "public/js/summernote.js", "public/js/summernote-zh-CN.js"], "public/js/all.js").version();

mix.copy("node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css", "public/css");
mix.copy("node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput-typeahead.css", "public/css");
mix.copy("node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.js", "public/js");