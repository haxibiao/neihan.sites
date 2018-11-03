<?php

namespace App\Console\Commands;

use App\Article;
use App\Category;
use App\Image;
use App\User;
use App\Video;
use App\Visit;
use Illuminate\Console\Command;

class FixData extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'fix:data {table}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'fix dirty data by table';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		if ($table = $this->argument('table')) {
			return $this->$table();
		}
		return $this->error("必须提供你要修复数据的table");
	}

	function videos() {
		$this->info('fix videos ...');
		$formatter = 'http://cos.ainicheng.com/storage/video/%d.jpg.%d.jpg';
		\App\Video::whereNull('qcvod_fileid')->where('status','>',0)->chunk(100, function ($videos) use($formatter) {
			foreach ($videos as $video) { 
				$covers = [];
				for ($i = 1; $i <= 8 ; $i++) {
					$str = sprintf($formatter, $video->id, $i);
					$covers[] = $str;
				}
				$updated_at = $video->updated_at;
				$video->timestamps = false;
				$video->setJsonData('covers', $covers);
			}
		});
		$this->info('fix videos finished...');
	}

	function categories() {
		$this->info('fix count_videos ...');
		foreach (Category::all() as $category) {
			$category->count_videos = $category->videoPosts()->count();
			$category->save();
		}
	}

	//图片迁移第一步：上传静态资源到COS
	function images1() {
		ini_set("memory_limit", "-1"); //取消PHP内存限制
		$this->info('fix images1 ...');
		//dd($images);
		$bucket = config("app.name");
		$cos = app('qcloudcos');
		//忽略下列文件
		$discard_files = [
			'.DS_Store',
			'.git',
		];
		/* 将img与image文件夹下的内容拷贝到COS */
		$disk = \Storage::disk('opendir');
		$images = [];
		if (config("app.name") != 'ainicheng') {
			$images = array_merge(
				$disk->allFiles('/storage/avatar'),
				$disk->allFiles('/storage/img'),
				$disk->allFiles('/storage/image'),
				$disk->allFiles('/storage/category')
			);
		}
		$videos = $disk->allFiles('/storage/video');
		$videos = array_filter($videos, function ($path) {
			return !str_contains($path, 'mp4');
		});
		$images = array_merge(
			$images,
			$videos
		);
		//上传的文件总数
		$sum = count($images);
		$error_count = 0; //记录传输失败的次数
		foreach (collect($images)->chunk(200) as $chunk) {
			foreach ($chunk as $name) {
				$time = $sum / 30 + $sum / 10;
				var_dump($name . '<<>>' . '剩余:' . $sum . '个文件,预计剩余时间:' . intval($time) . '秒');
				$base_name = basename($name);
				if (str_contains($base_name, $discard_files)) {
					continue;
				}
				$dstFpath = $name;
				$srcFpath = public_path($dstFpath);

				try {
					$result = $cos::upload($bucket, $srcFpath, $dstFpath);
					$result_obj = json_decode($result);
					if ($result_obj->code != 0) {
						//0:代表上传成功的状态
						$this->error('Upload Failure:' . $result_obj->message);
						$error_count++;
					}
				} catch (\Exception $e) {
					$error_count++;
					$this->error('图片上传至COS失败---->>>' . $e->getMessage());
					continue;
				}
				$sum--;
			}
			sleep(1);
		}
		$this->info('共:' . $sum . '个文件,失败:' . $error_count . '个文件');

	}
	//处理默认头像与老视频的封面图
	function images10() {
		ini_set("memory_limit", "-1"); //取消PHP内存限制
		$bucket = config("app.name");
		$cos = app('qcloudcos');
		//忽略下列文件
		$discard_files = [
			'.DS_Store',
			'.git',
			'.mp4',
		];
		$disk = \Storage::disk('opendir');
		$images = array_merge(
			$disk->allFiles('/storage/video'),
			$disk->allFiles('/img')
		);
		foreach (collect($images)->chunk(200) as $chunk) {
			foreach ($chunk as $name) {
				$base_name = basename($name);
				if (str_contains($base_name, $discard_files)) {
					continue;
				}
				var_dump($name);
				$dstFpath = $name;
				$srcFpath = public_path($dstFpath);
				try {
					$result = $cos::upload($bucket, $srcFpath, $dstFpath);
					$result_obj = json_decode($result);
					if ($result_obj->code != 0) {
						//0:代表上传成功的状态
						$this->error('Upload Failure:' . $result_obj->message);
					}
				} catch (\Exception $e) {
					$this->error('图片上传至COS失败---->>>' . $e->getMessage());
					continue;
				}
			}
			sleep(1);
		}

		//处理上传的默认头像
		for ($i = 1; $i <= 15; $i++) {
			$srcFpath = public_path('images/avatar-' . $i . '.jpg');
			$result = $cos::upload($bucket, $srcFpath, 'storage/avatar/avatar-' . $i . '.jpg');
			try {
				$result_obj = json_decode($result);
				if ($result_obj->code != 0) {
					//0:代表上传成功的状态
					$this->error('Upload Failure:' . $result_obj->message);
				}
			} catch (\Exception $e) {
				$this->error('头像上传至COS失败---->>>' . $e->getMessage());
				continue;
			}
		}
	}

	//处理articles表中冗余的image_url image_top
	function images2() {
		ini_set("memory_limit", "-1"); //取消PHP内存限制
		$path_formatter = 'http://cos.' . config("app.name") . '.com%s';
		Article::orderBy('id')->chunk(100, function ($articles) use ($path_formatter) {
			foreach ($articles as $article) {
				$img_path = $article->image_url;
				$this->info($img_path);
				$path_top = $article->image_top;
				if (empty($img_path)) {
					continue;
				}
				//忽略vod的图片
				if (str_contains($img_path, ['1251052432.vod2.myqcloud.com'])) {
					continue;
				}
				//非爬虫文章
				if (!str_contains($img_path, 'haxibiao')) {
					$replace_path = str_contains($img_path, env('APP_DOMAIN')) ?
					str_after($img_path, env('APP_DOMAIN'))
					:
					$img_path;
					$article->image_url = sprintf($path_formatter, $replace_path);
					if (!empty($path_top)) {
						$article->image_top = sprintf($path_formatter, $path_top);
					}
				} else {
					$replace_path = str_after($img_path, 'haxibiao.com');
					$article->image_url = sprintf('http://cos.haxibiao.com%s', $replace_path);
					//更新轮播图
					if (!empty($path_top)) {
						$article->image_top = sprintf('http://cos.haxibiao.com%s', $path_top);
					}
				}
				$article->timestamps = false;
				$article->save();
			}
		});
	}

	//图片迁移第二步：更新数据库记录中的图片路径
	function images3() {
		ini_set("memory_limit", "-1"); //取消PHP内存限制
		$this->info('fix images2 ...');
		//修改Image中的path与path_top
		$path_formatter = 'http://cos.' . config("app.name") . '.com%s';
		Image::orderBy('id')->chunk(100, function ($images) use ($path_formatter) {
			foreach ($images as $image) {
				$this->info($image->path);
				$disk = $image->disk;
				//脏数据，不处理
				if ($image->path == '.jpg' || empty($image->path)) {
					continue;
				}
				if ($disk != 'hxb') {
					//原图
					$image->path = sprintf($path_formatter, $image->path);
					//轮播图
					$path_top = $image->path_top;
					if (!empty($path_top)) {
						$image->path_top = sprintf($path_formatter, $image->path_top);
					}
					$image->disk = config("app.name"); //disk存放图片的bucket
					//source_url为空代表爬虫文章
				} else {
					$replace_path = str_contains($image->path, 'haxibiao.com') ?
					str_after($image->path, 'haxibiao.com') : $image->path;
					$image->path = sprintf('http://cos.haxibiao.com%s', $replace_path);
					//更新轮播图
					$path_top = $image->path_top;
					if (!empty($path_top)) {
						$image->path_top = sprintf('http://cos.haxibiao.com%s', $image->path_top);
					}
					$image->disk = 'haxibiao';
				}
				//$image->timestamps = false;
				$image->save();
			}
		});
		//修改Category的logo与logo_app的地址
		$logo_formatter = 'http://cos.' . config("app.name") . '.com%s';
		Category::orderBy('id')->chunk(1000, function ($categories) use ($logo_formatter) {
			foreach ($categories as $category) {
				$this->info($category->logo);
				$logo = $category->logo;
				$category->logo = sprintf($logo_formatter, $logo);
				$logo_app = $category->logo_app;
				if (!empty($logo_app)) {
					//category中logo_app有部分脏数据
					if (!str_contains($logo_app, ['/tmp'])) {
						$category->logo_app = sprintf($logo_formatter, $logo_app);
					}
				}
				$category->timestamps = false;
				$category->save();
			}
		});

		//修改User的avatar地址
		$avatar_formatter = 'http://cos.' . config("app.name") . '.com/storage/avatar/%s';
		User::chunk(1000, function ($users) use ($avatar_formatter) {
			foreach ($users as $user) {
				$this->info($user->avatar);
				$avatar = $user->avatar;
				//统一下各个站点的默认头像
				if (str_contains($avatar, ['default.jpg', 'avatar.jpg', 'editor_'])) {
					$user->avatar = sprintf($avatar_formatter, 'avatar-' . rand(1, 15) . '.jpg');
				} else {
					$user->avatar = sprintf($avatar_formatter, basename($avatar));
				}
				$user->timestamps = false;
				$user->save();
			}
		});
	}
	//图片迁移第三步：替换文章体中的图片
	function images4() {
		ini_set("memory_limit", "-1"); //取消PHP内存限制
		Article::chunk(1000, function ($articles) {
			foreach ($articles as $article) {
				$this->info($article->title);
				if (empty($article->body)) {
					continue;
				}
				//匹配正文中所有的图片路径
				$pattern = "/<img.*?src=['|\"](.*?)['|\"].*?[\/]?>/iu";
				preg_match_all($pattern, $article->body, $matches);
				$img_urls = end($matches);
				$body_html = $article->body;
				foreach ($img_urls as $img_url) {
					if (empty($img_url)) {
						continue;
					}
					$img_name = basename($img_url);
					if (empty($img_name)) {
						continue;
					}
					//爱你城本地的图片
					if (str_contains($img_url, env('APP_DOMAIN'))) {
						$formatter = 'http://cos.' . env('APP_DOMAIN') . '%s';
						$cdn_url = sprintf($formatter, str_after($img_url, env('APP_DOMAIN')));
						$body_html = str_replace($img_url, $cdn_url, $body_html);
						//哈希表的图片
					} elseif (str_contains($img_url, 'haxibiao.com')) {
						$formatter = 'http://cos.haxibiao.com%s';
						$cdn_url = sprintf($formatter, str_after($img_url, 'haxibiao.com'));
						$body_html = str_replace($img_url, $cdn_url, $body_html);
					}  elseif(starts_with($img_url,'/')) {
						//相对路径
						$formatter = 'http://cos.' . config("app.name") . '.com%s';
						$cdn_url = sprintf($formatter, $img_url);
						$body_html = str_replace($img_url, $cdn_url, $body_html);
					}
				}
				$article->body = $body_html;
				$article->timestamps = false;
				$article->save();
			}
		});
	}

	function articles() 
	{
		$this->info('fix artciles ing ...');
		//article 与video 作者不符合
		$article = Article::findOrFail(14609);
		$article->video_id = null;
		$article->type = 'article';
		$article->timestamps = false;
		$article->save();
		$this->info('fix success');
	}

	public function content($article) {
		$body = $article->body;
		$preg = "/<img.*?src=[\"|\'](.*?)[\"|\'].*?>/";
		preg_match_all($preg, $body, $matchs);
		$image_tag = $matchs[0][0];
		$image_url = $matchs[1][0];
		$preg = "/.*?thumbnail_(\d+)/";
		preg_match_all($preg, $image_url, $matchs);
		$video_id = $matchs[1][0];
		if (!empty($image_url)) {
			$article->body = str_replace($image_tag, '', $body);
			$article->status = -1;
			$article_categories = $article->categories()->get();
			$newArticle = Article::where('video_id', $video_id)->first();
			if ($newArticle) {
				foreach ($article_categories as $category) {
					$newArticle->categories()->attach([$category->id => [
						'created_at' => $category->pivot->created_at,
						'updated_at' => $category->pivot->updated_at,
						'submit' => $category->pivot->submit,
					]]);
				}
			}
			$article->save();
			$this->info('Article Id:' . $article->id . ' fix success');
		}
	}

	function collections() {
		$this->info('fix collections ...');
		Collection::chunk(100, function ($collections) {
			foreach ($collections as $conllection) {
				$conllection_id = $conllection->id;
				if (count($conllection->articles()->pluck('article_id')) > 0) {
					$article_id_arr = $conllection->articles()->pluck('article_id');
					foreach ($article_id_arr as $article_id) {
						$article = Article::find($article_id);
						$article->collection_id = $conllection_id;
						$article->save();
						$conllection->count_words += $article->count_words;
						$this->info('Artcile:' . $article_id . ' corresponding collections:' . $conllection_id);
					}
					$conllection->count = count($article_id_arr);
					$conllection->save();
				}
				//
			}
		});
		$this->info('fix collections success');
	}

	function article_comments() {
		//修复Article评论数据
		$this->info('fix article comments...');
		Comment::whereNull('comment_id', 'and', true)->chunk(100, function ($comments) {
			foreach ($comments as $comment) {
				if (empty(Comment::find($comment->comment_id))) {
					$article_id = $comment->commentable_id;
					$comment->delete();
					$this->info('文章： https://l.ainicheng.com/article/' . $article_id);
				}
			}
		});
		$this->info('fix articles count_comments...');
		//修复Article评论统计数据
		Article::chunk(100, function ($articles) {
			foreach ($articles as $article) {
				$article->count_replies = $article->comments()->count();
				$article->count_comments = $article->comments()->max('lou');
				$article->save();
			}
		});
		$this->info('fix success');
	}

	function visits() {
		//获取视频的最大ID
		$max_video_id= Video::max('id');

		$index = 0;
		$success_index = 0;
		$failed_index = 0;
		
		$visits = Visit::where('visited_type', 'videos')->where('visited_id', '>', $max_video_id)->get();
		foreach ($visits as $visit) {
			$index++;
			try{
				$video_id = Article::findOrFail($visit->visited_id)->video_id;
			}catch(\Exception $e){
				//如果这条记录不存在的话 删除掉这条浏览记录
				$visit_id = $visit->id;
				$visit->delete();
				$failed_index++;
				$this->error('visits ID:'.$visit_id.' delete');
				continue;
			}

			$visit->visited_id = $video_id;
			$visit->timestamps = false;
			$visit->save();
			$success_index++;

			$this->info(env('APP_DOMAIN') . ' visits Id:'.$visit->id . ' fix success');		
		}

		$this->info('总共fix数据:'.$index.'条,成功:'.$success_index.',失败:'.$failed_index);
	}

	function actions() {
		$this->info('fix article action');
		Article::where('status', '1')->chunk(100, function ($articles) {
			foreach ($articles as $article) {
				$article_id = $article->id;
				$acion_article = Action::where('actionable_type', 'articles')
					->where('actionable_id', $article_id)->get();
				if (!$acion_article->count()) {
					$action = Action::updateOrCreate([
						'user_id' => $article->user_id,
						'actionable_type' => 'articles',
						'actionable_id' => $article->id,
						'created_at' => $article->created_at,
						'updated_at' => $article->updated_at,
					]);
					$this->info('fix Article Id:' . $article->id . ' fix success');
				}
			}
		});
		$this->info('fix article action success');
	}

	function users() {
		//修复部分用户头像路径错误
		$sql = '%cos.'.env('APP_DOMAIN').'%';
		User::where('avatar','not like',$sql)->chunk(100, function($users){
			foreach ($users as $user) {
				$user->avatar = str_replace('http://cos.'.config('app.name').'/', 'http://cos.'.env('APP_DOMAIN').'/', $user->avatar);
				$user->timestamps = false;
				$user->save();
				$this->info(env('APP_DOMAIN').' user: '.$user->id.' fix success');
			}
		});
	}

	function notifications() {
		$this->info('fix notifications ....');
		DB::table('notifications')->whereType('App\Notifications\ArticleLiked')->orderByDesc('id')->chunk(100, function ($notifications) {
			foreach ($notifications as $notification) {
				$data = json_decode($notification->data);
				if (strpos($data->body, '视频') !== false && strpos($data->title, '《》') !== false) {
					try {
						$article = Article::findOrFail($data->article_id);
						$article_title = $article->title ?: $article->video->title;
						// 标题 视频标题都不存在 则取description
						if (empty($article_title)) {
							$article_title = $article->get_description();
						}
						$notification->timestamps = false;
						$result = DB::table('notifications')->where('id', $notification->id)
							->update(
								[
									'data->article_title' => $article_title,
									'data->title' => '《' . $article_title . '》',
								]
							);
						if ($result) {
							$this->info('notification ' . $notification->id . ' fix success');
						}
					} catch (\Exception $e) {
						continue;
					}
				}
			}
		});
		$this->info('fix success');
	}

	public function transactions() {
		// Transaction::whereType('打赏')->orderByDesc('id')->chunk(100,function($transactions) use(){
		//     foreach ($transactions as $transaction) {
		//         try{
		//             preg_match_all('#/(\d+)#', $transaction->log, $matches);
		//             $data = end($matches);
		//             $user = User::findOrFail(reset($data));
		//             $video = Video::findOrFail(end($data));
		//         }catch(\Exception $e){
		//             continue;
		//         }
		//         if(!empty($article = $video->article) && !empty($user)){
		//             if(strpos($transaction->log,'向您的') !== false && strpos($transaction->log,'《》') !== false){
		//                 $transaction->log = $user->link() . '向您的' . $article->link() . '打赏' . $amount . '元';
		//                 $transaction->timestamps=false;
		//                 $transaction->save();
		//                 $this->info('transaction '.$transaction->id.' fix success');
		//             }else if(strpos($transaction->log,'向<a') !==false && strpos($transaction->log,'《》') !== false){
		//                 $transaction->log = '向' . $article->user->link() . '的' . $article->link() . '打赏' . $amount . '元';
		//                 $transaction->timestamps=false;
		//                 $transaction->save();
		//                 $this->info('transaction '.$transaction->id.' fix success');
		//             }
		//         }
		//     }
		// });
		Transaction::whereType('打赏')->orderByDesc('id')->chunk(100, function ($transactions) {
			foreach ($transactions as $transaction) {
				if (strpos($transaction->log, '赏元') !== false) {
					$log = str_replace('赏元', '赏' . intval($transaction->amount) . '元', $transaction->log);
					$transaction->log = $log;
					$transaction->timestamps = false;
					$transaction->save();
					$this->info('transaction ' . $transaction->id . ' fix success');
				}
			}
		});
	}
}
