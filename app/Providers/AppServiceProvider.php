<?php

namespace App\Providers;

use Auth;
use Blade;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use PiwikTracker;

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
        Carbon::setLocale('zh');
        Schema::defaultStringLength(191);

        View::composer(
            '*',
            'App\Http\ViewComposers\SiteComposer'
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
        $this->app->singleton('piwik', function ($app) {
            return new PiwikTracker(env('MATOMO_SITE_ID'), env('MATOMO_URL'));
        });
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
            'withdraws'   => 'App\Withdraw',
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
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    public function registerSingleObject()
    {
        $this->app->singleton('DouyinSpider', function ($app) {
            return new \Haxibiao\Helpers\DouyinSpider();
        });
        $this->app->singleton('GuzzleClient', function ($app) {
            return new \GuzzleHttp\Client();
        });
        $this->app->singleton('ffmpeg', function ($app) {
            return \FFMpeg\FFMpeg::create([
                'ffmpeg.binaries'  => [
                    '/usr/local/bin/ffmpeg',
                    '/usr/local/ffmpeg/bin/ffmpeg',
                    '/usr/bin/ffmpeg',
                    exec('which ffmpeg'),
                ],
                'ffprobe.binaries' => [
                    '/usr/local/bin/ffprobe',
                    '/usr/local/ffmpeg/bin/ffprobe',
                    '/usr/bin/ffprobe',
                    exec('which ffprobe'),
                ],
            ]);
        });
        $this->app->singleton('ffprobe', function ($app) {
            return \FFMpeg\FFProbe::create([
                'ffprobe.binaries' => [
                    '/usr/local/bin/ffprobe',
                    '/usr/local/ffmpeg/bin/ffprobe',
                    '/usr/bin/ffprobe',
                    exec('which ffprobe'),
                ],
            ]);
        });
    }
}
