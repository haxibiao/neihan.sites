<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class SitemapController extends Controller
{
    public function index()
    {
        $siteMapContentExist = Storage::disk('public')->exists('sitemap/'.get_domain().'/sitemap.xml');
        if(!$siteMapContentExist){
            Artisan::call('sitemap:generate',['--domain'=>get_domain()]);
        }
        $siteMapContentPath = Storage::disk('public')->get('sitemap/'.get_domain().'/sitemap.xml');
        return response($siteMapContentPath)
            ->header('Content-Type', 'text/xml');
    }

    public function name_en($name_en){
        $siteMapContentPath = Storage::disk('public')->get('sitemap/'.get_domain().'/'.$name_en);
        return response($siteMapContentPath)
            ->header('Content-Type', 'application/octet-stream');
    }
}
