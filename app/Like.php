<?php

namespace App;

use App\Article;
use App\Model;
use App\Notifications\ArticleLiked;
use App\User;
use Cache;
use App\Events\LikeWasCreated;
use App\Events\LikeWasDeleted;
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
	 * @DateTime 2018-07-24
	 * @return   [type]     [description]
	 */
	public function toggleLike($input) {
		//只能简单创建
		$user = getUser(); 
		$like = Like::firstOrNew([
			'user_id' => $user->id,
			'liked_id' => $input['liked_id'],
			'liked_type' => $input['liked_type'],
		]);
		//取消喜欢
		if ( ($input['undo']??false) || $like->id ) {
			$like->delete();
			event(new LikeWasDeleted($like));
			$liked_flag = false;
		} else {
			$like->save();
			event(new LikeWasCreated($like));
			$liked_flag = true;
		}
		$like_obj = $like->liked;
		if($input['liked_type']=='comments'){
			$like_obj->liked = $liked_flag;
		}
		return $like_obj;
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
				->with(['user'=>function($query){
			        $query->select('id','name','avatar');
			    }])->paginate(10); 
		}
		return $data;
	}

	public function likedArticles() {
		if ($this->liked_type == 'articles') {
			return Article::find($this->liked_id);
		}
	}
}
