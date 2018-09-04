<?php

namespace App\Listeners;

use App\Events\LikeWasDeleted;

class DestroyedLike {
	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct() {
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  LikeWasDeleted  $event
	 * @return void
	 */
	public function handle(LikeWasDeleted $event) {
		$like = $event->like;
		$liked_obj = $like->liked;
		$authorizer = getUser();
		if ($like->liked_type == 'comments') {

			$liked_obj->likes = $liked_obj->likes()->count();
			$liked_obj->save();
			//下面代码注释的原因是：前端兄弟反应用户触发喜欢的操作频繁时，导致统计结果不正确：原因是前端没有记录下点击状态。目前用上面的代码来统计，但是效率不高(多一个表的关联，而且数据集比较大)。等后面需要优化的时候，可以在前端做优化，同时复用下面的代码。

			//$liked_obj->decrement('likes');
			//动态或文章
		} else if ($like->liked_type == 'articles') {

			$target_user = $liked_obj->user;
			$liked_obj->count_likes = $liked_obj
				->likes()
				->count();
			$liked_obj->save();
			$target_user->count_likes = $target_user
				->likes()
				->count();
			$target_user->save();

			//下面代码注释的原因是：前端兄弟反应用户频繁触发喜欢操作，导致统计结果不正确：原因是前端没有记录下点击状态。目前用上面的代码来统计，但是效率不高(多一个表的关联，而且数据集比较大)。等后面需要优化的时候，可以在前端做优化，同时复用下面的代码。

			//$liked_obj->decrement('count_likes');
			//$target_user->decrement('count_likes');

		}
		\App\Action::where([
			'user_id' => $authorizer->id,
			'actionable_type' => 'likes',
			'actionable_id' => $like->id,
		])->delete();
	}
}
