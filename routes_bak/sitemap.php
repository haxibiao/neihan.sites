<?php

Route::get('sitemap', function() {
	 if(!Cache::get('sitemap')){
        $controller = new \App\Http\Controllers\SitemapController();
        $controller->make();
        Cache::put('sitemap',1, 60);
	 }
	 return redirect()->to('/sitemap.xml');
});