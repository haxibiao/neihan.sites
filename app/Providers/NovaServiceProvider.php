<?php

namespace App\Providers;

use App\Nova\Metrics\UserAppVersionPartition;
use Illuminate\Support\Facades\Route;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        $this->withAuthenticationRoutes();
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /** 覆盖withAuthenticationRoutes() */
    public function withAuthenticationRoutes($middleware = ['web'])
    {

        \Route::namespace('App\Nova\Http\Controllers\Auth')
            ->domain(config('nova.domain', null))
            ->middleware($middleware)
            ->as('nova.')
            ->prefix(Nova::path())
            ->group(function () {
                Route::get('/login', 'LoginController@showLoginForm');
                Route::post('/login', 'LoginController@login')->name('login');
                Route::get('/logout', 'LoginController@logout')->name('logout');
            });
        return $this;
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        \Gate::define('viewNova', function ($user) {
            return $user->isAdmin();
        });
    }

    /**
     * Get the cards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new \App\Nova\Metrics\UsersPerDay,
            new \App\Nova\Metrics\ActiveUsersPerDay,
            new \App\Nova\Metrics\WithdrawsPerDay,
            // new \App\Nova\Metrics\ArticlePerDay,
            new \App\Nova\Metrics\PostPerDay,
            new \App\Nova\Metrics\PostSpiderPerDay,
            // new \App\Nova\Metrics\ArticleSpiderPerDay,
            new \App\Nova\Metrics\UserGender,
            new \App\Nova\Metrics\UserRetentionRate,
            // new \App\Nova\Metrics\ArticleCount,
            new \App\Nova\Metrics\UserCount,
            new \App\Nova\Metrics\WithDrawCount,
            new UserAppVersionPartition,
            new \App\Nova\Metrics\TodayAdData,
            new \App\Nova\Metrics\YesterdayAdData,
            // (new \Hxb\CategoryLikeCount\CategoryLikeCount)
            //     ->withName("受欢迎的分类前十个统计(视频点赞数)")
            //     ->withData(\App\Category::getTopLikeCategory(10)),
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            // new \PhpJunior\NovaLogViewer\Tool(), //因为每日生产的log有权限问题，暂时不用这个
            // new \Llaski\NovaScheduledJobs\NovaScheduledJobsTool,
            // new \KABBOUCHI\LogsTool\LogsTool,
            new \Acme\PriceTracker\PriceTracker(),
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
