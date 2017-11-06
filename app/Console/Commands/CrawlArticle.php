<?php

namespace App\Console\Commands;

use App\Article;
use Goutte\Client;
use Illuminate\Console\Command;

class CrawlArticle extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'crawl:article {--categoryID=} {--articleID=}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->cralwer = new Client();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		if ($this->option('categoryID')) {
			$category_id = $this->option('categoryID');
			$this->get_category($category_id);
		}
		if ($this->option('articleID')) {
			$article_id = $this->option('articleID');
			$this->get_article($article_id);
		}
	}
//https://haxibiao.com/api/articles?cate_id=12?page=2
	public function get_category($category_id) {

		$i = 1;
		$url = "https://haxibiao.com/api/articles?cate_id=$category_id?page=$i";
		$json = file_get_contents($url);
		$json = json_decode($json);
		$total = ($json->total);
		for ($i = 1; $i <= $total; $i++) {
			$url = "https://haxibiao.com/api/articles?cate_id=$category_id?page=$i";
			$json = file_get_contents($url);
			$json = json_decode($json);
			foreach ($json->data as $js) {
				$article = Article::firstOrNEW([
					'title' => $js->title,
				]);
				$article->body = $js->body;
				$article->status = 1;
				$article->author = "汤圆";
				$article->user_name = "汤圆";
				$article->user_id = 15;
				$article->description = "";
				$article->keywords = "王者荣耀";
				$article->image_url = "https://haxibiao.com$js->image_url";
				$article->category_id = 22;
				$article->json = $js->json;
				if ($article->id) {
					$article->update();
					$this->comment('已更新:' . $article->title);
				} else {
					$article->save();
					$this->info('已导入:' . $article->title);
				}
			}
		}
	}
}