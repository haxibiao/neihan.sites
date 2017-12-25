<?php

namespace App\Http\Controllers\Api;

use App\Action;
use App\Favorite;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request, $id, $type)
    {
        $user   = $request->user();
        $result = 0;

        $favorite = Favorite::firstOrNew([
            'user_id'    => $user->id,
            'faved_id'   => $id,
            'faved_type' => get_polymorph_types($type),
        ]);

        if ($favorite->id) {
            $favorite->delete();
        } else {
            $favorite->save();
            $result = 1;

            //action
            $action = Action::firstOrNew([
                'user_id'         => $user->id,
                'actionable_type' => 'favorites',
                'actionable_id'   => $favorite->id,
            ]);
            $action->save();
        }

        $user->count_favorites = $user->favorites()->count();
        $user->save();
        return $result;
    }

    public function get(Request $request, $id, $type)
    {
        $favorite = Favorite::firstOrNew([
            'user_id'    => $request->user()->id,
            'faved_id'   => $id,
            'faved_type' => $type,
        ]);
        return $favorite->id;
    }
}
