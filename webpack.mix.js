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

require(`${__dirname}/resources/themes/huaijiugangju/webpack.mix.js`);

require(`${__dirname}/webpack.mix.default.js`);

mix.browserSync("l.diudie.com");
