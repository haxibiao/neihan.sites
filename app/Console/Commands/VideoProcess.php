<?php

namespace App\Console\Commands;

use App\Video;
use Illuminate\Console\Command;

class VideoProcess extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'video:process {--id=}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'process video in ways: local, vod(default)';

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
		if ($video_id = $this->option('id')) {
			return $this->processVideo($video_id);
		}
		$this->processDrafts();
	}

	public function processVideo($video_id) {
		$video = Video::with('article')->findOrFail($video_id);
		//covers sync
		//video_urls 流畅，标清，高清，Full HD, sync
		//TODO 下架又封面的视频也会执行下面的逻辑，需要设置标志位过滤掉。
		$video->syncVodProcessResult();

		//同步文章封面
		if ($article = $video->article) {
			$article->image_url = $video->cover;
			if (!empty($video->jsonData('covers'))) {
				$article->status = 1;
				$video->status = 1;
			}
			$article->save();
			$video->title = $article->title;
			$video->save();
		} else {
			$this->error("好奇怪，文章不存在");
			$video->status = -1;
			$video->save();
		}
		$this->info("$video->id $article->title $video->path $video->cover");
	}

	public function processDrafts() {
		$videos = Video::where('status', 0)->get();
		foreach ($videos as $video) {
			if (!str_contains($video->path, 'vod') || empty($video->qcvod_file)) {
				//旧的无效视频，进入软删除状态
				$video->status = -1;
				$video->save();
			}
			$this->processVideo($video->id);
		}
	}
}
