<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {
		Schema::defaultStringLength(191);

		View::composer(
			'*', 'App\Http\ViewComposers\SiteComposer'
		);
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		Relation::morphMap([
			'articles' => 'App\Article',
			'videos' => 'App\Video',
			'likes' => 'App\Like',
			'favorites' => 'App\Favorite',
		]);
		foreach (glob(app_path() . '/Helpers/*.php') as $filename) {
			require_once $filename;
		}
	}
}
