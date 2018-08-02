<?php

namespace App;

use App\Article;
use App\Model;
use App\Notifications\ArticleLiked;
use App\User;
use Illuminate\Support\Facades\Cache;

class Like extends Model {
	protected $fillable = [
		'user_id',
		'liked_id',
		'liked_type',
	];

	public function user() {
		return $this->belongsTo(\App\User::class);
	}

	public function liked() {
		return $this->morphTo();
	}
	/* --------------------------------------------------------------------- */
	/* ------------------------------- service ----------------------------- */
	/* --------------------------------------------------------------------- */
	/**
	 * @Desc     喜欢/不喜欢
	 * TODO 由于项目开发紧张，对评论点赞的功能没重构进来了。后面有时间再重构吧
	 * @DateTime 2018-07-24
	 * @return   [type]     [description]
	 */
	public function toggleLike($input) {
		//只能简单创建
		$user = getUser();
		$article = Article::with('user')
			->findOrFail($input['liked_id']);
		$author = $article->user;

		$like = Like::firstOrNew([
			'user_id' => $user->id,
			'liked_id' => $input['liked_id'],
			'liked_type' => $input['liked_type'],
		]);
		//取消喜欢
		if ((isset($input['undo']) && $input['undo']) || $like->id) {
			$like->delete();
			//喜欢
		} else {
			$like->save();
			//消息通知
			$cacheKey = 'user_' . $user->id . '_like_' . $like->liked_id . '_' . $like->liked_type;
			if (!Cache::get($cacheKey) && ($author->id != $user->id)) {
				$author->notify(new ArticleLiked($like->liked_id, $user->id));
				$author->forgetUnreads();
				Cache::put($cacheKey, 1, 60);
			}
			//记录操作日志
			$action = Action::updateOrCreate([
				'user_id' => $user->id,
				'actionable_type' => 'likes',
				'actionable_id' => $like->id,
			]);
		}
		//更新冗余数据
		$article->count_likes = $article->likes()->count();
		$article->save();

		$author->count_likes = $author->articles()->sum('count_likes');
		$author->save();
	}
	/**
	 * @Desc     获取喜欢的用户
	 * @DateTime 2018-07-24
	 * @return   [type]     [description]
	 */
	public function likeUsers($input) {
		if (checkUser()) {
			$user = getUser();
			$input['user_id'] = $user->id;
			$like = Like::firstOrNew($input);
			$data['is_liked'] = $like->id;
		}
		$data['likes'] = [];
		if ($input['liked_type'] == 'articles') {
			$article = Article::findOrFail($input['liked_id']);
			$data['likes'] = $article->likes()
				->with('user')
				->paginate(10);
		}
		return $data;
	}

	public function likedArticles() {
		if ($this->liked_type == 'articles') {
			return Article::find($this->liked_id);
		}
	}
}
