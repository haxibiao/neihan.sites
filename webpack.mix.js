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
    .js("resources/assets/js/app2.js", "public/js")
    .sass("resources/assets/sass/simditor/simditor.scss", "public/css")
    .sass("resources/assets/sass/app.scss", "public/css");


//css
mix
    .styles(
        [
            "public/css/app.css",            
            "node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css",
            "public/fonts/iconfont.css"
        ],
        "public/css/a2.css"
    )
    .version();



// v1 
mix
    .scripts([
            "public/js/app.js",
            "node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.js",
            "resources/assets/js/plugins/poster.js"
        ], 
        "public/js/a.js")
    .version();


mix.js("resources/assets/js/spa.js", "public/js/b.js");

// v2
mix
    .scripts([
            "public/js/app2.js",
            "resources/assets/js/plugins/poster.js"
        ], 
        "public/js/a2.js")
    .version();


mix.js("resources/assets/js/spa2.js", "public/js/b2.js");

mix.browserSync('l.ainicheng.com');