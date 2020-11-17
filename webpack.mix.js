let mix = require("laravel-mix");
let { env } = require("minimist")(process.argv.slice(2));
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
mix.styles(["public/css/app.css", "public/fonts/iconfont.css"], "public/css/guest.css").version();

//editor.css
mix.styles(["public/css/simditor.css", "public/css/write.css"], "public/css/editor.css").version();

if (env && env.css) {
  return;
}

// ====== js
mix.js("resources/assets/js/main.js", "public/js");
mix.babel("resources/assets/js/plugins/poster.js", "public/js/poster.js");
mix.copy("resources/assets/js/plugins/jquery-form.js", "public/js/jquery-form.js");

//spa.js
mix.js("resources/assets/js/spa.js", "public/js").version();

//write.js
mix.js("resources/assets/js/write.js", "public/js").version();


//app.js
mix.js("resources/assets/js/app.js", "public/js/_app.js");
mix
  .scripts(
    [
      "public/js/_app.js",
      "public/js/poster.js",
      "resources/assets/js/plugins/bootstrap-tagsinput.js",
      "public/js/jquery-form.js",
      "resources/assets/js/plugins/at.js",
      "resources/assets/js/plugins/jquery.caret.js",
    ],
    "public/js/app.js"
  )
  .version();

mix.browserSync("localhost:8000");
