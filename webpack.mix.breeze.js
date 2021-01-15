let mix = require('laravel-mix');
let { env } = require('minimist')(process.argv.slice(2));
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

// 主题 - 怀旧港剧
// require(`${__dirname}/packages/haxibiao/breeze/resources/themes/huaijiugangju/webpack.mix.js`);

//电影模块 css
mix.sass('packages/haxibiao/breeze/resources/assets/media/sass/movie.scss', 'public/css/movie').version();
mix.sass('packages/haxibiao/breeze/resources/assets/media/sass/movie/home.scss', 'public/css/movie').version();
mix.sass('packages/haxibiao/breeze/resources/assets/media/sass/movie/play.scss', 'public/css/movie').version();
mix.sass('packages/haxibiao/breeze/resources/assets/media/sass/movie/search.scss', 'public/css/movie').version();
mix.sass('packages/haxibiao/breeze/resources/assets/media/sass/movie/category.scss', 'public/css/movie').version();
mix.sass('packages/haxibiao/breeze/resources/assets/media/sass/movie/favorites.scss', 'public/css/movie').version();

//电影模块 js
mix.js('packages/haxibiao/breeze/resources/assets/media/js/movie.js', 'public/js/movie').version();
mix.js('packages/haxibiao/breeze/resources/assets/media/js/play.js', 'public/js/movie/_play.js').version();
mix.scripts(['public/js/movie/_play.js', 'node_modules/hls.js/dist/hls.js'], 'public/js/movie/play.js');
mix.js('packages/haxibiao/breeze/resources/assets/media/js/home.js', 'public/js/movie').version();

// 内容模块 css
mix.sass('packages/haxibiao/breeze/resources/assets/content/sass/app.scss', 'public/css')
    .version()
    .sass('packages/haxibiao/breeze/resources/assets/content/sass/write.scss', 'public/css')
    .version()
    .sass('packages/haxibiao/breeze/resources/assets/content/sass/simditor/simditor.scss', 'public/css')
    .version();
mix.styles(['public/css/app.css', 'public/fonts/iconfont.css'], 'public/css/guest.css').version();
mix.styles(['public/css/simditor.css', 'public/css/write.css'], 'public/css/editor.css').version();

// 内容模块 js
mix.babel('packages/haxibiao/breeze/resources/assets/content/js/plugins/poster.js', 'public/js/poster.js');
mix.copy('packages/haxibiao/breeze/resources/assets/content/js/plugins/jquery-form.js', 'public/js/jquery-form.js');

//spa.js
mix.js('packages/haxibiao/breeze/resources/assets/content/js/spa.js', 'public/js').version();

//write.js
mix.js('packages/haxibiao/breeze/resources/assets/content/js/write.js', 'public/js').version();

//app.js
mix.js('packages/haxibiao/breeze/resources/assets/content/js/app.js', 'public/js/_app.js');
mix.scripts(
    [
        'public/js/_app.js',
        'public/js/poster.js',
        'public/js/jquery-form.js',
        'packages/haxibiao/breeze/resources/assets/content/js/plugins/bootstrap-tagsinput.js',
        'packages/haxibiao/breeze/resources/assets/content/js/plugins/at.js',
        'packages/haxibiao/breeze/resources/assets/content/js/plugins/jquery.caret.js',
    ],
    'public/js/app.js',
).version();

mix.browserSync('l.diudie.com');
