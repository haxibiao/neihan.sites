<?php

namespace App\Http\Controllers\Api;

use App\Action;
use App\Follow;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FollowController extends Controller
{
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

            //保存action
            $user->actions()->save(new Action([
                'user_id'         => $user->id,
                'actionable_type' => 'follows',
                'actionable_id'   => $follow->id,
            ]));

        }
        $follow->followed->count_follows = $follow->followed->follows()->count();
        $follow->followed->save();

        return $result;
    }

    public function follows(Request $request)
    {
        $user    = $request->user();
        $follows = [];
        foreach ($user->followings as $follow) {
            $follow['id']   = $follow->followed->id;
            $follow['name'] = $follow->followed->name;
            $follow['type'] = $follow->followed_type;
            $follow['img']  = $follow->followed_type == 'categories' ? $follow->followed->logo : $follow->followed->avatar();
            $follows[]      = $follow;
        }

        return $follows;
    }

    public function recommands(Request $request)
    {
        $user=$request->user();
        $data['user']=$user;
        $data['command']=[];

        $followed_users = $user->followings()->where('followed_type','users')->orderBy('id','desc')->take(10)->get();
        foreach($followed_users as $follow) {
            $followed_user = $follow->followed;
            foreach($followed_user->followings as $follow) {
                $followed = $follow->followed;
                if($follow->followed_type == 'users') {
                    $followed->collections = $followed->collections()->take(2)->get();
                }

                $followed->is_followed = $user->isFollow($follow->followed_type, $follow->followed_id);

                $followed->type = $follow->followed_type;

                $followed->followed_user = $followed_user->name;
                $followed->followed_user_id = $followed_user->id;

                $data['recommends'][] = $followed;
            }
        }

        $recommended_users = User::orderBy('id','desc')->paginate(10);
        foreach($recommended_users as $recommended_user) {
            $recommended_user->followed = $user->isFollow('users', $recommended_user->id);
            $recommended_user->avatar = $recommended_user->avatar();
            $recommended_user->collections = $recommended_user->collections()->take(2)->get();
        }
        $data['recommended_users'] = $recommended_users;


        $categories = Category::orderBy('id', 'desc')->paginate(10);
        foreach($categories as $category) {
            $category->followed = $user->isFollow('categories', $category->id);
        }
        $data['recommended_categories'] = $categories;

        return $data;
    }
}
