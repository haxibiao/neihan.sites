<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SitemapController extends Controller
{
    public function index()
    {
        $siteMapContentExist = Storage::disk('public')->exists('sitemap/'.get_domain().'/sitemap.xml');
        if(!$siteMapContentExist){
            Artisan::call('sitemap:generate',['--domain'=>get_domain()]);
        }
        $siteMapContentPath = Storage::disk('public')->get('sitemap/'.get_domain().'/sitemap.xml');
        if(!$siteMapContentPath){
            abort(404);
        }
        return response($siteMapContentPath)
            ->header('Content-Type', 'text/xml');
    }

    public function name_en($name_en){
        // XML
        $endWithXml = ends_with($name_en,'.xml');
        if($endWithXml){
            $siteMapContent = Storage::disk('public')->get('sitemap/'.get_domain().'/'.$name_en);
            if(!$siteMapContent){
                abort(404);
            }
            return response($siteMapContent)
                ->header('Content-Type', 'text/xml');
        }
        // GZ
        $siteMapContent = Storage::disk('public')->get('sitemap/'.get_domain().'/'.$name_en);
        if(!$siteMapContent){
            abort(404);
        }
        return response($siteMapContent)
            ->header('Content-Type', 'application/octet-stream');
    }
}
