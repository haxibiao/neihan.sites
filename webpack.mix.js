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
        "public/css/a.css"
    )
    .version();

mix
    .scripts([
            "public/js/app.js", 
            "public/js/hack.js",        
            "node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.js",
            "node_modules/summernote/dist/summernote.js",
            "node_modules/summernote/dist/lang/summernote-zh-CN.js",
            "resources/assets/js/util.js"
        ], 
        "public/js/a.js")
    .version();


mix.js("resources/assets/js/spa.js", "public/js");
mix
    .scripts(
        [
            "public/js/spa.js",
            "public/js/hack.js",
            "node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.js",
            "node_modules/summernote/dist/summernote.js",
            "node_modules/summernote/dist/lang/summernote-zh-CN.js",
            "resources/assets/js/util.js"
        ],
        "public/js/b.js"
    )
    .version();






// v2
mix
    .js("resources/assets/js2/app2.js", "public/js")
    .sass("resources/assets/sass2/app2.scss", "public/css");

mix
    .styles(
        [
            "public/css/app2.css",
            "node_modules/summernote/dist/summernote.css",
            "node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css",
            "public/fonts/iconfont.css",
            "resources/assets/css/hack.css"
        ],
        "public/css/a2.css"
    )
    .version();

mix
    .scripts([
            "public/js/app2.js",   
            "node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.js",
            "node_modules/summernote/dist/summernote.js",
            "node_modules/summernote/dist/lang/summernote-zh-CN.js",
            "resources/assets/js2/util.js"
        ], 
        "public/js/a2.js")
    .version();


mix.js("resources/assets/js2/spa2.js", "public/js");
mix
    .scripts(
        [
            "public/js/spa2.js",
            "node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.js",
            "node_modules/summernote/dist/summernote.js",
            "node_modules/summernote/dist/lang/summernote-zh-CN.js",
            "resources/assets/js2/util.js"
        ],
        "public/js/b2.js"
    )
    .version();

mix.browserSync('l.ainicheng.com');