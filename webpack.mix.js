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
mix
    .js("resources/assets/js/app.js", "public/js")
    .sass("resources/assets/sass/app.scss", "public/css");

mix.copy("node_modules/summernote/dist/font/*", "public/css/font");

mix
    .styles(
        [
            "public/css/app.css",
            "node_modules/summernote/dist/summernote.css",
            "node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css",
            "resources/assets/css/hack.css",
            "public/fonts/iconfont.css"
        ],
        "public/css/app.css"
    )
    .version();

mix
    .scripts([
            "public/js/app.js", 
            "public/js/hack.js",        
            "node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.js",
            "node_modules/summernote/dist/summernote.js",
            "node_modules/summernote/dist/summernote-zh-CN.js"
        ], 
        "public/js/app.js")
    .version();


mix.js("resources/assets/js/spa.js", "public/js");
mix
    .scripts(
        [
            "public/js/spa.js",
            "public/js/hack.js",
            "node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.js",
            "node_modules/summernote/dist/summernote.js",
            "node_modules/summernote/dist/summernote-zh-CN.js"
        ],
        "public/js/spa.js"
    )
    .version();

mix.browserSync('l.ainicheng.com');