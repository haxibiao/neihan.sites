<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function getIndex()
    {
        $videos = Video::orderBy('id','desc')->paginate(12);
        return $videos;
    }

    public function saveRelation(Request $request, $id)
    {
        $video = Video::findOrFail($id);
        $data  = json_decode($video->json);
        if (empty($data)) {
            $data = [];
        }
        $data[]      = $request->all();
        $video->json = json_encode($data);
        $video->save();

        return $video;
    }

    public function getAllRelations(Request $request, $id)
    {
        $video     = Video::findOrFail($id);
        $contoller = new \App\Http\Controllers\VideoController();
        return $contoller->get_json_lists($video);
    }

    public function deleteRelation(Request $request, $id, $key)
    {
        $video = Video::findOrFail($id);
        $data  = json_decode($video->json);
        if (empty($data)) {
            $data = [];
        }
        $data_new = [];
        foreach ($data as $k => $list) {
            if ($k == $key) {
                continue;
            }
            $data_new[] = $list;
        }

        $video->json = json_encode($data_new);
        $video->save();

        return $data_new;
    }

    public function getRelation($id, $key)
    {
        $video = Video::findOrFail($id);
        $json  = json_decode($video->json, true);
        if (array_key_exists($key, $json)) {
            $data = $json[$key];
            if (empty($data['type']) || $data['type'] == 'single_list') {
                $items = [];
                if (is_array($data['aids'])) {
                    foreach ($data['aids'] as $aid) {
                        $video = Video::find($aid);
                        if ($video) {
                            $items[] = [
                                'id'        => $video->id,
                                'title'     => $video->title,
                                'image_url' => get_img($video->cover),
                            ];
                        }
                    }
                }
                $data['items'] = $items;
            }

            return $data;
        }
        return null;
    }
}
