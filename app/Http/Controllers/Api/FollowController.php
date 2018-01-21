<?php
namespace App\Http\Controllers\Api;

use App\Action;
use App\Category;
use App\Follow;
use App\Http\Controllers\Controller;
use App\Notifications\UserFollowed;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FollowController extends Controller
{
    public function touch(Request $request, $id, $type)
    {
        $user   = $request->user();
        $result = 0;
        $follow = Follow::firstOrNew([
            'user_id'       => $user->id,
            'followed_id'   => $id,
            'followed_type' => get_polymorph_types($type),
        ]);

        if ($follow->id) {
            $follow->touch();
            return 1;
        }
        return 0;
    }
    public function toggle(Request $request, $id, $type)
    {
        $user   = $request->user();
        $result = 0;
        $follow = Follow::firstOrNew([
            'user_id'       => $user->id,
            'followed_id'   => $id,
            'followed_type' => get_polymorph_types($type),
        ]);
        if ($follow->id) {
            $follow->delete();
        } else {
            $follow->save();
            $result = 1;

            //record action
            $user->actions()->save(new Action([
                'user_id'         => $user->id,
                'actionable_type' => 'follows',
                'actionable_id'   => $follow->id,
            ]));

            //notify when user follow
            if (get_polymorph_types($type) == 'users') {
                //避免短时间内重复提醒
                $cacheKey = 'user_' . $user->id . '_follow_' . $type . '_' . $id;
                if(!Cache::get($cacheKey)){
                $followed_user = $follow->followed;
                $followed_user->notify(new UserFollowed($user));
                $followed_user->forgetUnreads();
                Cache::put($cacheKey, 1, 60);
              }
            }
        }

        $follow->followed->count_follows = $follow->followed->follows()->count();
        $follow->followed->save();

        $user->count_followings = $user->followings()->count();
        $user->save();

        return $result;
    }

    public function follows(Request $request)
    {
        $user    = $request->user();
        $follows = [];
        foreach ($user->followings as $item) {
            $follow['id']   = $item->followed->id;
            $follow['name'] = $item->followed->name;
            $follow['type'] = $item->followed_type;
            $follow['img']  = $item->followed_type == 'categories' ?
            $item->followed->logo : $item->followed->avatar;
            $updates           = $item->followed->articles()->where('articles.created_at', '>', $item->updated_at)->count();
            $follow['updates'] = $updates ? $updates : '';
            $follows[]         = $follow;
        }

        return $follows;
    }

    public function recommends(Request $request)
    {
        $user               = $request->user();
        $data['user']       = $user;
        $data['recommends'] = [];

        $followed_users = $user->followings()->where('followed_type', 'users')->orderBy('id', 'desc')->take(10)->get();

        foreach ($followed_users as $follow) {
            $followed_user = $follow->followed;
            foreach ($followed_user->followings as $follow) {
                $followed = $follow->followed;
                if ($follow->followed_type == 'users') {
                    $followed->collections = $followed->collections()->take(2)->get();
                }

                $followed->is_followed = $user->isFollow($follow->followed_type, $follow->followed_id);

                $followed->type = $follow->followed_type;

                $followed->followed_user    = $followed_user->name;
                $followed->followed_user_id = $followed_user->id;

                $data['recommends'][] = $followed;
            }
        }

        $recommended_users = User::orderBy('id', 'desc')->paginate(10);
        foreach ($recommended_users as $recommended_user) {
            $recommended_user->followed    = $user->isFollow('users', $recommended_user->id);
            $recommended_user->collections = $recommended_user->collections()->take(2)->get();
        }
        $data['recommended_users'] = $recommended_users;

        $categories = Category::orderBy('id', 'desc')->paginate(10);
        foreach ($categories as $category) {
            $category->followed = $user->isFollow('categories', $category->id);
        }
        $data['recommended_categories'] = $categories;

        return $data;
    }
}
