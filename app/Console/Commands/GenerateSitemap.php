<?php


namespace App\Console\Commands;


use App\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Sitemap;
use Spatie\Sitemap\Tags\Url;

/**
 *  一个sitemap文件最多5w个链接,所有sitmap文件大家加起来不能超过50M
 *
 */
class GenerateSitemap  extends Command
{
    protected $signature = 'sitemap:generate {--domain=}';

    protected $description = 'Generate the sitemap.';

    public function handle()
    {
        $domain = $this->option('domain');
        $neihanSitesDomains = array_keys(neihan_sites_domains());
        if($domain){
//            if(!in_array($domain,$neihanSitesDomains)){
//                return $this->error("输入的参数不合法");
//            }
            return self::generateSingleSitemap($domain);
        }

        foreach ($neihanSitesDomains as $neihanSitesDomain){
            self::generateSingleSitemap($neihanSitesDomain);
        }
    }

    private function generateSingleSitemap($domain){
        $siteMapIndexUrls = array_merge(
            self::generateMovies($domain),
            self::generateIssues($domain),
            self::generateCategory($domain),
            self::generateArticles($domain),
            self::generateVideos($domain)
        );
        $siteMapIndex = SitemapIndex::create();
        foreach ($siteMapIndexUrls as $siteMapIndexUrl){
            $siteMapIndex->add(Sitemap::create('https://www.'.$domain.'/sitemap'.$siteMapIndexUrl)
                ->setLastModificationDate(Carbon::yesterday()));
        }
        $siteMapIndex->writeToDisk('public', 'sitemap/'.$domain.'/sitemap.xml');
        return $this->info($domain."已经成功生成sitemap");
    }

    private function generateMovies($domain){
        $siteMapIndexUrls = [];
        $mi = 0;
        DB::table('movies')->select(['id'])
            ->orderBy('id','desc')->chunk(10000,function ($movies)use(&$mi,&$siteMapIndexUrls,$domain){
                $sitemapGenerator = SitemapGenerator::create($domain)
                    ->getSitemap();
                foreach ($movies as $movie){
                    $sitemapGenerator->add(Url::create('https://www.'.$domain.'/movie/'.$movie->id)
                        ->setLastModificationDate(Carbon::yesterday())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setPriority(0.8)
                    );
                }
                $sitemapGenerator->writeToDisk('public', 'sitemap/'.$domain.'/movie_'.$mi.'.xml');
                $siteMapIndexUrls[] = '/movie_'.$mi.'.xml';
                $mi++;
            });
        return $siteMapIndexUrls;
    }

    private function generateIssues($domain){
        $siteMapIndexUrls = [];
        $qi = 0;
        DB::table('issues')->select(['id'])
            ->whereNull('deleted_at')
            ->orderBy('id','desc')->chunk(10000,function ($questions)use(&$qi,&$siteMapIndexUrls,$domain){
                $sitemapGenerator = SitemapGenerator::create($domain)
                    ->getSitemap();
                foreach ($questions as $question){
                    $sitemapGenerator->add(Url::create('https://www.'.$domain.'/question/'.$question->id)
                        ->setLastModificationDate(Carbon::yesterday())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setPriority(0.8)
                    );
                }
                $sitemapGenerator->writeToDisk('public', 'sitemap/'.$domain.'/question_'.$qi.'.xml');
                $qi++;
                $siteMapIndexUrls[] = '/question_'.$qi.'.xml';
            });
        return $siteMapIndexUrls;
    }

    private function generateCategory($domain){
        $siteMapIndexUrls = [];
        $ci = 0;
        Category::where('status',1)->where('count','>',0)->chunk(10000,function ($categories)use(&$ci,&$siteMapIndexUrls,$domain){
            $sitemapGenerator = SitemapGenerator::create($domain)
                ->getSitemap();
            foreach ($categories as $category){
                $sitemapGenerator->add(Url::create('https://www.'.$domain.'/category/'.$category->id)
                    ->setLastModificationDate(Carbon::yesterday())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(0.8)
                );
            }
            $sitemapGenerator->writeToDisk('public', 'sitemap/'.$domain.'/category_'.$ci.'.xml');
            $ci++;
            $siteMapIndexUrls[] = '/category_'.$ci.'.xml';
        });
        return $siteMapIndexUrls;
    }

    private function generateArticles($domain){
        $siteMapIndexUrls = [];
        $ai = 0;
        DB::table('articles')->select(['id'])
            ->where('status',1)
            ->whereNull('deleted_at')
            ->whereIn('type',['article','diagrams'])->orderBy('id','desc')->chunk(10000,function ($articles)use(&$ai,&$siteMapIndexUrls,$domain){
                $sitemapGenerator = SitemapGenerator::create($domain)
                    ->getSitemap();
                foreach ($articles as $article){
                    $sitemapGenerator->add(Url::create('https://www.'.$domain.'/article/'.$article->id)
                        ->setLastModificationDate(Carbon::yesterday())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setPriority(0.8)
                    );
                }
                $sitemapGenerator->writeToDisk('public', 'sitemap/'.$domain.'/article_'.$ai.'.xml');
                $ai++;
                $siteMapIndexUrls[] = '/articles_'.$ai.'.xml';
            });
        return $siteMapIndexUrls;
    }

    private function generateVideos($domain){
        $siteMapIndexUrls = [];
        $vi = 0;
        DB::table('videos')->whereStatus(1)->whereNull('deleted_at')
            ->select(['id'])
            ->orderBy('id','desc')->chunk(10000,function ($videos)use(&$vi,&$siteMapIndexUrls,$domain){
                $sitemapGenerator = SitemapGenerator::create($domain)
                    ->getSitemap();
                foreach ($videos as $video){
                    $sitemapGenerator->add(Url::create('https://www.'.$domain.'/video/'.$video->id)
                        ->setLastModificationDate(Carbon::yesterday())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setPriority(0.8)
                    );
                }
                $sitemapGenerator->writeToDisk('public', 'sitemap/'.$domain.'/video_'.$vi.'.xml');
                $vi++;
                $siteMapIndexUrls[] = '/video_'.$vi.'.xml';
            });
        return $siteMapIndexUrls;
    }
}
