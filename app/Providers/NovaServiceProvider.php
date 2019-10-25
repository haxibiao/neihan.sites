<?php

namespace App\Providers;

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

        \Route::namespace ('App\Nova\Http\Controllers\Auth')
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
            return $user->is_admin || ends_with($user->email, '@haxibiao.com') || ends_with($user->account, '@haxibiao.com');
            ;
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
            new \App\Nova\Metrics\NewUsers,
            new \App\Nova\Metrics\UsersPerDay,
            new \App\Nova\Metrics\NewWithdraw,
            new \App\Nova\Metrics\UserGender,
            new \App\Nova\Metrics\ArticlePerDay,
            new \App\Nova\Metrics\ActiveUsersPerDay,
            new \App\Nova\Metrics\WithdrawsPerDay,
            new \App\Nova\Metrics\UserRetentionRate,
            (new \Hxb\CategoryLikeCount\CategoryLikeCount)
                ->withName("受欢迎的分类前十个统计(视频点赞数)")
                ->withData(\App\Category::getTopLikeCategory(10)),
            // (new \Hxb\CategoryCount\CategoryCount)
            //     ->withName("分类下的视频数量前十个统计")
            //     ->withData(\App\Category::getTopCategory(10)),
            // new \Llaski\NovaScheduledJobs\NovaScheduledJobsCard,
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
