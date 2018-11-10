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

// ====== css
mix.sass("resources/assets/sass/app.scss", "public/css")
	.sass("resources/assets/sass/write.scss", "public/css")
	.sass("resources/assets/sass/simditor/simditor.scss", "public/css");

//guest a.css
mix.styles(["public/css/app.css", "public/fonts/iconfont.css"], "public/css/guest.css").version();

//login:
//editor.css
mix.styles(["public/css/simditor.css", "public/css/write.css"], "public/css/editor.css").version();

