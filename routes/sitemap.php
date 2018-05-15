<?php

use Illuminate\Support\Facades\Cache;

Route::get('sitemap', function () {
    if (!Cache::get('sitemap')) {
        $controller = new \App\Http\Controllers\SitemapController();
        $controller->make();
        Cache::put('sitemap', 60);
    }
    return redirect()->to('/sitemap.xml');
});
