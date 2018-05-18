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
mix
	.sass("resources/assets/sass/app.scss", "public/css")
	.sass("resources/assets/sass/write.scss", "public/css")
	.sass("resources/assets/sass/simditor/simditor.scss", "public/css");

//guest a.css
mix.styles(["public/css/app.css", "public/fonts/iconfont.css"], "public/css/a.css").version();

//login:
//editor.css
mix.styles(["public/css/simditor.css", "public/css/write.css"], "public/css/editor.css").version();

// ====== js
mix.js("resources/assets/js/app.js", "public/js");

//guest.js
mix.scripts(["public/js/app.js", "resources/assets/js/plugins/poster.js"], "public/js/guest.js").version();

// test graphql
mix.js("resources/assets/js/g.js", "public/js").version();

//login:
//user.js
mix.js("resources/assets/js/user.js", "public/js/_user.js");
mix
	.scripts(
		["public/js/_user.js", "resources/assets/js/plugins/poster.js", "resources/assets/js/plugins/bootstrap-tagsinput.js"],
		"public/js/user.js"
	)
	.version();

//editor.js
mix.js("resources/assets/js/editor.js", "public/js/_editor.js");
mix
	.scripts(
		["public/js/_editor.js", "resources/assets/js/plugins/poster.js", "resources/assets/js/plugins/bootstrap-tagsinput.js"],
		"public/js/editor.js"
	)
	.version();

//spa.js
mix.js("resources/assets/js/spa.js", "public/js").version();

//write.js
mix.js("resources/assets/js/write.js", "public/js").version();

//admin.js
mix.js("resources/assets/js/admin.js", "public/js/_admin.js");
mix
	.scripts(
		["public/js/_admin.js", "resources/assets/js/plugins/poster.js", "resources/assets/js/plugins/bootstrap-tagsinput.js"],
		"public/js/admin.js"
	)
	.version();

mix.browserSync("localhost:8000");
