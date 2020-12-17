<?php

namespace App\Console\Commands;

use App\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Sitemap;
use Spatie\Sitemap\Tags\Url;
use wapmorgan\UnifiedArchive\UnifiedArchive;

/**
 *  一个sitemap文件最多5w个链接,所有sitmap文件大家加起来不能超过50M
 *
 */
class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate {--domain=}';

    protected $description = 'Generate the sitemap.';

    public function handle()
    {
        $domain             = $this->option('domain');
        $neihanSitesDomains = array_keys(neihan_sites_domains());
        if ($domain) {
//            if(!in_array($domain,$neihanSitesDomains)){
            //                return $this->error("输入的参数不合法");
            //            }
            return $this->generateSingleSitemap($domain);
        }

        foreach ($neihanSitesDomains as $neihanSitesDomain) {
            $this->generateSingleSitemap($neihanSitesDomain);
        }
    }

    private function generateSingleSitemap($domain)
    {
        $siteMapIndexUrls = array_merge(
            $this->generateMovies($domain),
            $this->generateIssues($domain),
            $this->generateCategory($domain),
            $this->generateArticles($domain),
            $this->generateVideos($domain)
        );
        $siteMapIndex = SitemapIndex::create();
        foreach ($siteMapIndexUrls as $siteMapIndexUrl) {
            $siteMapIndex->add(Sitemap::create('https://' . $domain . '/sitemap' . $siteMapIndexUrl)
                    ->setLastModificationDate(Carbon::yesterday()));
        }
        $siteMapIndex->writeToDisk('public', 'sitemap/' . $domain . '/sitemap.xml');
        return $this->info($domain . "已经成功生成sitemap");
    }

    private function generateMovies($domain)
    {
        $siteMapIndexUrls = [];
        $mi               = 0;

        DB::table('movies')->select(['id'])
            ->orderBy('id', 'desc')->chunk(10000, function ($movies) use (&$mi, &$siteMapIndexUrls, $domain) {
            $fileName     = 'movie_' . $mi . '.xml';
            $gzFileName   = $fileName . '.gz';
            $relativePath = 'sitemap/' . $domain . '/' . $fileName;

            $sitemapGenerator = SitemapGenerator::create($domain)
                ->getSitemap();
            foreach ($movies as $movie) {
                $sitemapGenerator->add(Url::create('https://' . $domain . '/movie/' . $movie->id)
                        ->setLastModificationDate(Carbon::yesterday())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setPriority(0.8)
                );
            }

            $sitemapGenerator->writeToDisk('public', $relativePath);
            $path = Storage::disk('public')->path($relativePath);
            if (file_exists($path . '.gz')) {
                unlink($path . '.gz');
            }
            UnifiedArchive::archiveFile($path, $path . '.gz');
            $siteMapIndexUrls[] = '/' . $fileName;
            $mi++;
        });
        return $siteMapIndexUrls;
    }

    private function generateIssues($domain)
    {
        $siteMapIndexUrls = [];
        $qi               = 0;
        DB::table('issues')->select(['id'])
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')->chunk(10000, function ($questions) use (&$qi, &$siteMapIndexUrls, $domain) {
            $fileName     = 'question_' . $qi . '.xml';
            $gzFileName   = $fileName . '.gz';
            $relativePath = 'sitemap/' . $domain . '/' . $fileName;

            $sitemapGenerator = SitemapGenerator::create($domain)
                ->getSitemap();
            foreach ($questions as $question) {
                $sitemapGenerator->add(Url::create('https://' . $domain . '/question/' . $question->id)
                        ->setLastModificationDate(Carbon::yesterday())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setPriority(0.8)
                );
            }
            $sitemapGenerator->writeToDisk('public', $relativePath);
            $path = Storage::disk('public')->path($relativePath);
            if (file_exists($path . '.gz')) {
                unlink($path . '.gz');
            }
            UnifiedArchive::archiveFile($path, $path . '.gz');
            $siteMapIndexUrls[] = '/' . $fileName;
            $qi++;
        });
        return $siteMapIndexUrls;
    }

    private function generateCategory($domain)
    {
        $siteMapIndexUrls = [];
        $ci               = 0;
        Category::where('status', 1)->where('count', '>', 0)->chunk(10000, function ($categories) use (&$ci, &$siteMapIndexUrls, $domain) {
            $fileName     = 'category_' . $ci . '.xml';
            $gzFileName   = $fileName . '.gz';
            $relativePath = 'sitemap/' . $domain . '/' . $fileName;

            $sitemapGenerator = SitemapGenerator::create($domain)
                ->getSitemap();
            foreach ($categories as $category) {
                $sitemapGenerator->add(Url::create('https://' . $domain . '/category/' . $category->id)
                        ->setLastModificationDate(Carbon::yesterday())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setPriority(0.8)
                );
            }
            $sitemapGenerator->writeToDisk('public', $relativePath);
            $path = Storage::disk('public')->path($relativePath);
            if (file_exists($path . '.gz')) {
                unlink($path . '.gz');
            }
            UnifiedArchive::archiveFile($path, $path . '.gz');

            $siteMapIndexUrls[] = '/' . $fileName;
            $ci++;
        });
        return $siteMapIndexUrls;
    }

    private function generateArticles($domain)
    {
        $siteMapIndexUrls = [];
        $ai               = 0;
        DB::table('articles')->select(['id'])
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->whereIn('type', ['article', 'diagrams'])->orderBy('id', 'desc')->chunk(10000, function ($articles) use (&$ai, &$siteMapIndexUrls, $domain) {

            $fileName     = 'article_' . $ai . '.xml';
            $gzFileName   = $fileName . '.gz';
            $relativePath = 'sitemap/' . $domain . '/' . $fileName;

            $sitemapGenerator = SitemapGenerator::create($domain)
                ->getSitemap();
            foreach ($articles as $article) {
                $sitemapGenerator->add(Url::create('https://' . $domain . '/article/' . $article->id)
                        ->setLastModificationDate(Carbon::yesterday())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setPriority(0.8)
                );
            }
            $sitemapGenerator->writeToDisk('public', $relativePath);
            $path = Storage::disk('public')->path($relativePath);
            if (file_exists($path . '.gz')) {
                unlink($path . '.gz');
            }
            UnifiedArchive::archiveFile($path, $path . '.gz');
            $siteMapIndexUrls[] = '/' . $fileName;
            $ai++;

        });
        return $siteMapIndexUrls;
    }

    private function generateVideos($domain)
    {
        $siteMapIndexUrls = [];
        $vi               = 0;
        DB::table('posts')->select(['video_id'])->whereNull('deleted_at')
            ->whereStatus(1)
            ->orderBy('id', 'desc')->chunk(20000, function ($videos) use (&$vi, &$siteMapIndexUrls, $domain) {
            $fileName     = 'video_' . $vi . '.xml';
            $gzFileName   = $fileName . '.gz';
            $relativePath = 'sitemap/' . $domain . '/' . $fileName;

            $sitemapGenerator = SitemapGenerator::create($domain)
                ->getSitemap();
            foreach ($videos as $video) {
                $sitemapGenerator->add(Url::create('https://' . $domain . '/video/' . $video->video_id)
                        ->setLastModificationDate(Carbon::yesterday())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setPriority(0.8)
                );
            }
            $sitemapGenerator->writeToDisk('public', $relativePath);
            $path = Storage::disk('public')->path($relativePath);
            if (file_exists($path . '.gz')) {
                unlink($path . '.gz');
            }
            UnifiedArchive::archiveFile($path, $path . '.gz');
            $siteMapIndexUrls[] = '/' . $fileName;
            $vi++;
        });
        return $siteMapIndexUrls;
    }
}
