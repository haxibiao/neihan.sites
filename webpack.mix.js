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

// css和js都打包了一份新的
mix
	.js("resources/assets/js/app.js", "public/js")
	.js("resources/assets/js/g.js", "public/js")
	.sass("resources/assets/sass/app.scss", "public/css")
	.sass("resources/assets/sass/write.scss", "public/css")
	.sass("resources/assets/sass/simditor/simditor.scss", "public/css");

//css
mix.styles(["public/css/app.css", "public/fonts/iconfont.css"], "public/css/a.css").version();

mix.styles(["public/css/simditor.css", "public/css/write.css"], "public/css/e.css").version();

mix
	.scripts(["public/js/app.js", "resources/assets/js/plugins/bootstrap-tagsinput.js", "resources/assets/js/plugins/poster.js"], "public/js/a.js")
	.version();

//js
mix.js("resources/assets/js/spa.js", "public/js/b.js");
//write
mix.js("resources/assets/js/write.js", "public/js/write.js");

mix.browserSync("localhost:8000");
