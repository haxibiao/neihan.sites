let mix = require("laravel-mix");
let { env } = require("minimist")(process.argv.slice(2));

// huaijiugangju assets
mix.js('resources/themes/huaijiugangju/assets/js/app.js', 'public/huaijiugangju/js');
mix.js('resources/themes/huaijiugangju/assets/js/base.js', 'public/huaijiugangju/js');
mix.js('resources/themes/huaijiugangju/assets/js/_bootstrap.js', 'public/huaijiugangju/js');
mix.js('resources/themes/huaijiugangju/assets/js/movie_components.js', 'public/huaijiugangju/js');
mix.js('resources/themes/huaijiugangju/assets/js/movie/home.js', 'public/huaijiugangju/movie/js');
mix.sass('resources/themes/huaijiugangju/assets/sass/app.scss', 'public/huaijiugangju/css');
mix.sass('resources/themes/huaijiugangju/assets/sass/base.scss', 'public/huaijiugangju/css');
mix.sass('resources/themes/huaijiugangju/assets/sass/_bootstrap.scss', 'public/huaijiugangju/css');
mix.sass('resources/themes/huaijiugangju/assets/sass/movie/home.scss', 'public/huaijiugangju/css/movie');
mix.sass('resources/themes/huaijiugangju/assets/sass/movie/play.scss', 'public/huaijiugangju/css/movie');
mix.sass('resources/themes/huaijiugangju/assets/sass/movie/search.scss', 'public/huaijiugangju/css/movie');
mix.sass('resources/themes/huaijiugangju/assets/sass/movie/category.scss', 'public/huaijiugangju/css/movie');
mix.sass('resources/themes/huaijiugangju/assets/sass/movie/detail.scss', 'public/huaijiugangju/css/movie');
mix.sass('resources/themes/huaijiugangju/assets/sass/movie/star.scss', 'public/huaijiugangju/css/movie');
