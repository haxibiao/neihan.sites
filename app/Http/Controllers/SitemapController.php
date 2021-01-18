<?php

namespace App\Http\Controllers;


use App\Article;
use App\Category;
use App\Movie;
use App\Question;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public $sitemap;

    public function index()
    {
        $sitemap = $this->sitemap->add(Url::create("/")
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1));
        $this->sitemap = $sitemap;

        return $this;
    }

    public function category()
    {
        $categories = Category::query()->get();

        foreach ($categories as $category) {
            $props   = ['hot', 'time', 'score'];
            $sitemap = $this->sitemap;
            foreach ($props as $prop) {
                $sitemap = $sitemap->add(Url::create("/category/{$category->id}")
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
                ->setPriority(0.7));
        $this->sitemap = $sitemap;

        return $this;
    }

    public function articles()
    {
        Article::where('created_at', '>', today()->subDay(2))->where('status', '>=', Article::SUBMITTED_SUBMIT)->chunkById(100, function ($articles) {
            $sitemap = $this->sitemap;
            foreach ($articles as $article) {
                \info("添加文章" . $article->id);
                $sitemap = $this->sitemap->add(Url::create("/article/{$article->id}")
                        ->setLastModificationDate($article->updated_at ?? now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setPriority(0.9));
            }
            $this->sitemap = $sitemap;
            return $this;
        });
    }

    public function questions()
    {
        $questions = Question::query()->chunkById(100, function ($questions) {
            $sitemap = $this->sitemap;
            foreach ($questions as $question) {
                $sitemap = $this->sitemap->add(Url::create("/question/{$question->id}")
                        ->setLastModificationDate($question->updated_at ?? now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setPriority(0.9));
            }
            $this->sitemap = $sitemap;
            return $this;
        });
    }

    public function movies()
    {
        $movies = Movie::query()->where('status', 1)->chunkById(100, function ($movies) {
            $sitemap = $this->sitemap;
            foreach ($movies as $movie) {
                $sitemap = $this->sitemap->add(Url::create("/movie/{$movie->id}")
                        ->setLastModificationDate($movie->updated_at ?? now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setPriority(0.7));
            }
            $this->sitemap = $sitemap;
            return $this;
        });
    }

    public function initialize()
    {
        $this->sitemap = Sitemap::create();
    }

    public function checkSitemap($site, $type)
    {
        //每周自动生成最新的站点地图
        if (file_exists(public_path("sitemap/{$site}_{$type}.xml"))) {
            $time = filemtime(public_path("sitemap/{$site}_{$type}.xml"));
            $date = null;
            if ($time) {
                $date = date("Y-m-d H:i:s", $time);
                //如果文件存在并且创建日期小于一周 那么不创建新的文件
                return now()->diffInDays(\Carbon\Carbon::parse($date)) > 7 ? true : false;
            }
        }
        return true;
    }

    public function all($site, $type)
    {
        \info("解析站点" . $site);
        \info("解析类型" . $type);
        if ($this->checkSitemap($site, $type)) {
            $this->initialize();
            $this->index();
            if ($type === "article") {
                $this->articles();
            } else if ($type == "video") {
                $this->videos();
            } else if ($type == "movie") {
                $this->movies();
            } else if ($type == "category") {
                $this->categories();
            } else if ($type == "question") {
                $this->question();
            }

            \info("开始写入");
            $this->sitemap->writeToFile(public_path("sitemap/{$site}_{$type}.xml"));
            $str = file_get_contents("sitemap/{$site}_{$type}.xml");
            $str = str_replace(env('APP_NAME'), "{$site}", $str);
            file_put_contents("sitemap/{$site}_{$type}.xml", $str);
            return redirect("/sitemap/{$site}_{$type}.xml");
        } else {
            return redirect("/sitemap/{$site}_{$type}.xml");
        }
    }
}
