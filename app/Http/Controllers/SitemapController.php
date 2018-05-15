<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SitemapController extends Controller
{
    public function index()
    {
        if (!Cache::get('sitemap')) {
            $this->make();
            Cache::put('sitemap', 1, 60*24);
        }
        return redirect()->to('/sitemap.xml');
    }

    public function make()
    {
        // create new sitemap object
        $sitemap = App::make('sitemap');

        // counters
        $counter        = 0;
        $sitemapCounter = 0;

        // get all products from db (or wherever you store them)
        DB::table('articles')->orderBy('created_at', 'desc')->where('status', '>', 0)->chunk(100, function ($articles) use (&$sitemap, &$counter, &$sitemapCounter) {

            // add every product to multiple sitemaps with one sitemap index
            foreach ($articles as $article) {
                if ($counter == config('sitemap')['map_file_size']) {
                    // generate new sitemap file
                    $sitemap->store('xml', 'sitemap-' . $sitemapCounter);
                    // add the file to the sitemaps array
                    $sitemap->addSitemap(secure_url('sitemap-' . $sitemapCounter . '.xml'));
                    // reset items array (clear memory)
                    $sitemap->model->resetItems();
                    // reset the counter
                    $counter = 0;
                    // count generated sitemap
                    $sitemapCounter++;
                }

                // add product to items array
                $sitemap->add('/article/' . $article->id, $article->updated_at, 1.0, 'daily');
                // count number of elements
                $counter++;
            }

        });

        // you need to check for unused items
        if (!empty($sitemap->model->getItems())) {
            // generate sitemap with last items
            $sitemap->store('xml', 'sitemap-' . $sitemapCounter);
            // add sitemap to sitemaps array
            $sitemap->addSitemap(secure_url('sitemap-' . $sitemapCounter . '.xml'));
            // reset items array
            $sitemap->model->resetItems();
        }

        //问答加入sitemap
        // counters
        $counter        = 0;
        $sitemapCounter = 0;

        // get all products from db (or wherever you store them)
        DB::table('questions')->orderBy('created_at', 'desc')->where('status', '>=', 0)->chunk(100, function ($questions) use (&$sitemap, &$counter, &$sitemapCounter) {

            // add every product to multiple sitemaps with one sitemap index
            foreach ($questions as $question) {
                if ($counter == config('sitemap')['map_file_size']) {
                    // generate new sitemap file
                    $sitemap->store('xml', 'sitemap-question-' . $sitemapCounter);
                    // add the file to the sitemaps array
                    $sitemap->addSitemap(secure_url('sitemap-question-' . $sitemapCounter . '.xml'));
                    // reset items array (clear memory)
                    $sitemap->model->resetItems();
                    // reset the counter
                    $counter = 0;
                    // count generated sitemap
                    $sitemapCounter++;
                }

                // add product to items array
                $sitemap->add('/question/' . $question->id, $question->updated_at, 1.0, 'daily');
                // count number of elements
                $counter++;
            }

        });

        // you need to check for unused items
        if (!empty($sitemap->model->getItems())) {
            // generate sitemap with last items
            $sitemap->store('xml', 'sitemap-question-' . $sitemapCounter);
            // add sitemap to sitemaps array
            $sitemap->addSitemap(secure_url('sitemap-question-' . $sitemapCounter . '.xml'));
            // reset items array
            $sitemap->model->resetItems();
        }

        // generate new sitemapindex that will contain all generated sitemaps above
        $sitemap->store('sitemapindex', 'sitemap');
    }
}
