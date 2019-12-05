<?php

namespace App\Providers;

use Auth;
use Blade;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        // 升级laravel 5.8 注释
        // $this->app->alias('bugsnag.multi', \Illuminate\Contracts\Logging\Log::class);
        // $this->app->alias('bugsnag.multi', \Psr\Log\LoggerInterface::class);

        Schema::defaultStringLength(191);

        View::composer(
            '*', 'App\Http\ViewComposers\SiteComposer'
        );

        Blade::directive('timeago', function ($expression) {
            return "<?php echo diffForHumansCN($expression); ?>";
        });
        //将秒数转换成 分:秒
        Blade::directive('sectominute', function ($expression) {
            return "<?php echo gmdate('i:s', $expression); ?>";
        });
        Blade::if('admin', function () {
            return Auth::check() && Auth::user()->checkAdmin();
        });

        Blade::if('editor', function () {
            return Auth::check() && Auth::user()->checkEditor();
        });

        Blade::if('weixin', function () {
            return request('weixin');
        });

        $this->registerSingleObject();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Relation::morphMap([
            'users'       => 'App\User',
            'categories'  => 'App\Category',
            'collections' => 'App\Collection',
            'articles'    => 'App\Article',
            'comments'    => 'App\Comment',
            'videos'      => 'App\Video',
            'posts'       => 'App\Article',
            'likes'       => 'App\Like',
            'favorites'   => 'App\Favorite',
            'follows'     => 'App\Follow',
            'tips'        => 'App\Tip',
            'questions'   => 'App\Issue',
            'answers'     => 'App\Resolution',
            'feedbacks'   => 'App\Feedback',
            'usertasks'   => 'App\UserTask',
        ]);

        foreach (glob(app_path() . '/Helpers/*.php') as $filename) {
            require_once $filename;
        }
        foreach (glob(app_path() . '/Helpers/*/*.php') as $filename) {
            require_once $filename;
        }
        foreach (glob(app_path() . '/../ops/helpers/*.php') as $filename) {
            require_once $filename;
        }
    }

    public function registerSingleObject()
    {
        $this->app->singleton('DouyinSpider', function ($app) {
            return new \App\Helpers\DouyinSpider();
        });
    }
}
