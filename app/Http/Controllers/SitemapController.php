<?php

namespace App\Http\Controllers;

use App\Category;
use App\Movie;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Artisan;
// use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Str;

class SitemapController extends Controller
{
    public $sitemap;

    public function index()
    {
        $sitemap = $this->sitemap->add(Url::create("/")
            ->setLastModificationDate(now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(1));
        $this->sitemap =  $sitemap;

        return $this;
    }

    public function category()
    {
        $categories = Category::query()->get();

        foreach ($categories as $category) {
            $props = ['hot', 'time', 'score'];
            $sitemap = $this->sitemap;
            foreach ($props as $prop) {
                $sitemap =  $sitemap->add(Url::create("/category/{$category->id}?order={$prop}")
                    ->setLastModificationDate($category->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(0.8));
            }
            $this->sitemap = $sitemap;
        }

        return $this;
    }

    public function search()
    {
        $sitemap = $this->sitemap->add(Url::create("/movie/search")
            ->setLastModificationDate(now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.9));
        $this->sitemap = $sitemap;

        return $this;
    }

    public function movie()
    {
        $movies = Movie::query()->get();
        $sitemap = $this->sitemap;
        foreach ($movies as $movie) {
            $sitemap = $this->sitemap->add(Url::create("/movie/{$movie->id}")
                ->setLastModificationDate($movie->updated_at ?? now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.7));
        }

        $this->sitemap =  $sitemap;

        return $this;
        // $sitemap->writeToFile(public_path("sitemap/{$type}-movie.xml"));

        // return redirect("/sitemap/{$type}-movie.xml");

    }

    public function initialize()
    {
        $this->sitemap = Sitemap::create();
    }

    public function checkSitemap($type)
    {
        //每周自动生成最新的站点地图
        if (file_exists(public_path("sitemap/{$type}.xml"))) {
            $time =  filemtime(public_path("sitemap/{$type}.xml"));
            $date = null;
            if ($time) {
                $date = date("Y-m-d H:i:s", $time);
                //如果文件存在并且创建日期小于一周 那么不创建新的文件
                return  now()->diffInDays(\Carbon\Carbon::parse($date)) > 7 ? true : false;
            }
        }
        return true;
    }

    public function all($type)
    {
        if ($this->checkSitemap($type)) {
            $this->initialize();
            $this->index()->search()->category()->movie();
            $this->sitemap->writeToFile(public_path("sitemap/{$type}.xml"));
            return redirect("/sitemap/{$type}.xml");
        } else {
            return redirect("/sitemap/{$type}.xml");
        }
    }

    // public function index()
    // {
    //     $siteMapContentExist = Storage::disk('public')->exists('sitemap/'.get_domain().'/sitemap.xml');
    //     if(!$siteMapContentExist){
    //         Artisan::call('sitemap:generate',['--domain'=>get_domain()]);
    //     }
    //     $siteMapContentPath = Storage::disk('public')->get('sitemap/'.get_domain().'/sitemap.xml');
    //     if(!$siteMapContentPath){
    //         abort(404);
    //     }
    //     return response($siteMapContentPath)
    //         ->header('Content-Type', 'text/xml');
    // }

    // public function name_en($name_en){
    //     // XML
    //     $endWithXml = ends_with($name_en,'.xml');
    //     if($endWithXml){
    //         $siteMapContent = Storage::disk('public')->get('sitemap/'.get_domain().'/'.$name_en);
    //         if(!$siteMapContent){
    //             abort(404);
    //         }
    //         return response($siteMapContent)
    //             ->header('Content-Type', 'text/xml');
    //     }
    //     // GZ
    //     $siteMapContent = Storage::disk('public')->get('sitemap/'.get_domain().'/'.$name_en);
    //     if(!$siteMapContent){
    //         abort(404);
    //     }
    //     return response($siteMapContent)
    //         ->header('Content-Type', 'application/octet-stream');
    // }
}
