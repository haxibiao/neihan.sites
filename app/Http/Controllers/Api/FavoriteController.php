<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Favorite;

class FavoriteController extends Controller
{
    public function save(Request $request, $id, $type)
    {
        $favorite = Favorite::firstOrNew([
            'user_id'   => $request->user()->id,
            'object_id' => $id,
            'type'      => $type,
        ]);
        $favorite->save();
        return $favorite->id;
    }

    public function delete(Request $request, $id, $type)
    {
        $favorite = Favorite::firstOrNew([
            'user_id'   => $request->user()->id,
            'object_id' => $id,
            'type'      => $type,
        ]);
        $favorite->delete();
        return 1;
    }

    public function get(Request $request, $id, $type)
    {
        $favorite = Favorite::firstOrNew([
            'user_id'   => $request->user()->id,
            'object_id' => $id,
            'type'      => $type,
        ]);
        return $favorite->id;
    }
}
