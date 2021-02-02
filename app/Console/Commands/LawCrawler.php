<?php

namespace App\Console\Commands;

use App\Article;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

class LawCrawler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:law {topic} {--cycles=} {--index=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '法律知识文章爬虫, 目标站点(http://www.64365.com/zs/)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $topic = $this->argument('topic'); // 话题 例如：婚姻家庭->结婚;  hyjt/jiehun
        $index = $this->option('index'); // 索引起始位置, 主要用于重试
        $cycles = $this->option('cycles'); // 抓取次数

        for ($i = $index; $i <= $cycles; $i++) {
            // 获取文章列表
            $articleDetailURL = $this->getArticles($index, $topic);

            // 保存文章详情
            foreach ($articleDetailURL as $url) {

                $articleHtml = $this->sendRequest($url);
                $articleCrawler = new Crawler($articleHtml);

                $article = Article::firstOrCreate(
                    // 标题
                    ['title' => $articleCrawler->filter('body > div.w1200 > div.mt30.clearfix > div.w810.fl > div:nth-child(1) > h1')->text()],
                    [
                        // 作者
                        'author' => $articleCrawler->filter('body > div.w1200 > div.mt30.clearfix > div.w810.fl > div:nth-child(1) > div.mt15.clearfix > div.s-cb.lh24 > span:nth-child(1)')->text(),
                        // 正文
                        'body' => $articleCrawler->filter('body > div.w1200 > div.mt30.clearfix > div.w810.fl > div:nth-child(1) > div.unfold-bar.detail-unfold.mt40 > div > div > div.detail-conts')->html(),
                        'user_id' => 2,
                    ]);
                $this->info($article->title . ' 保存成功 🕷️');
            }
        }
        return 0;
    }

    /**
     * 发送请求
     */
    private function sendRequest($targetUrl, $retry = 0)
    {
        // 仅重试三次
        if ($retry == 3) {
            $this->error($targetUrl . ' 该文章无法访问');
            return null;
        }
        try {
            $client = new Client([
                'defaults' => [
                    'config' => [
                        'curl' => [
                            CURLOPT_SSLVERSION => CURL_SSLVERSION_SSLv3,
                        ],
                    ],
                ],
            ]);

            $response = $client->request('GET', $targetUrl);
            return $response->getBody()->getContents();
        } catch (RequestException $e) {
            $retry++;
            $this->error($targetUrl . ' 请求失败, 第' . $retry . '次重试');
            $this->sendRequest($targetUrl, $retry++);
        }
    }

    /**
     * 爬虫的起点, 开始获取指定话题下的文章列表
     */
    private function getArticles($offset, $topic)
    {
        $result = [];
        // 发送请求
        $topicUrl = 'http://www.64365.com/zs/' . $topic;
        if ($offset != 1) {
            $topicUrl = 'http://www.64365.com/zs/' . $topic . '/' . $offset;
        }
        $articleHtml = $this->sendRequest($topicUrl);
        $articleCrawler = new Crawler($articleHtml);

        // 解析文章列表
        if ($articleCrawler->filter('#sw_3 > ul > li:nth-child(1) > div.tit.ect.fb > a')->count() == 0) {
            return $result;
        }

        $articleList = $articleCrawler->filter('#sw_3 > ul > li:nth-child(n) ')->each(function ($node) {
            return $node->filter('div.tit.ect.fb > a')->attr('href');
        });

        // 拼接文章详情 URL 链接
        foreach ($articleList as $article) {
            $result[] = 'http://www.64365.com' . $article;
        }

        return $result;
    }
}
