<?php

namespace App\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Carbon\Carbon;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
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

        // Paginator::useTailwind();
        Paginator::useBootstrap();
        // Paginator::useBootstrapThree();

        Carbon::setLocale('zh');
        Schema::defaultStringLength(191);

        View::composer(
            '*',
            'App\Http\ViewComposers\SiteComposer'
        );

        $this->registerSingleObject();
        // production环境url全部https
        if (is_prod()) {
            \URL::forceScheme('https');
        }
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
            'answers'     => 'App\Solution',
            'resolutions' => 'App\Solution',
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
