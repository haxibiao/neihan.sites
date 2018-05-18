<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Blade;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->alias('bugsnag.logger', \Illuminate\Contracts\Logging\Log::class);
        $this->app->alias('bugsnag.logger', \Psr\Log\LoggerInterface::class);

        Schema::defaultStringLength(191);

        View::composer(
            '*', 'App\Http\ViewComposers\SiteComposer'
        );

        Blade::directive('timeago', function ($expression) {
            return "<?php echo diffForHumansCN($expression); ?>";
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
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Relation::morphMap([
            'users' => 'App\User',
            'categories' => 'App\Category',
            'collections' => 'App\Collection',
            'articles' => 'App\Article',
            'comments' => 'App\Comment',
            'videos' => 'App\Video',
            'likes' => 'App\Like',
            'favorites' => 'App\Favorite',
            'follows' => 'App\Follow',
            'tips' => 'App\Tip',
            'questions' => 'App\Question',
            'answers' => 'App\Answer',
        ]);

        foreach (glob(app_path() . '/Helpers/*.php') as $filename) {
            require_once $filename;
        }
        foreach (glob(app_path() . '/../ops/helpers/*.php') as $filename) {
            require_once $filename;
        }
    }
}
