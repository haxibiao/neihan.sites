<?php

namespace App\Traits;

use App\Category;
use App\Helpers\QcloudUtils;
use Illuminate\Support\Facades\DB;

trait CategoryRepo
{
    public function fillForJs()
    {
        $this->logo        = $this->logoUrl;
        $this->description = $this->description();
    }

    public function topAdmins()
    {
        $topAdmins = $this->admins()->orderBy('id', 'desc')->take(10)->get();
        foreach ($topAdmins as $admin) {
            $admin->isCreator = $admin->id == $this->user_id;
        }
        return $topAdmins;
    }

    public function topAuthors()
    {
        $topAuthors = $this->authors()->orderBy('id', 'desc')->take(8)->get();
        return $topAuthors;
    }

    public function topFollowers()
    {
        $topFollows   = $this->follows()->orderBy('id', 'desc')->take(8)->get();
        $topFollowers = [];
        foreach ($topFollows as $follow) {
            $topFollowers[] = $follow->user;
        }
        return $topFollowers;
    }

    //TODO:待重构repo
    public function saveLogo($request)
    {
        $name = $this->id . '_' . time();
        if ($request->logo) {
            $file                = $request->logo;
            $extension           = $file->getClientOriginalExtension();
            $file_name_formatter = $name . '.%s.' . $extension;
            //save logo
            $file_name_big = sprintf($file_name_formatter, 'logo');
            $tmp_big       = '/tmp/' . $file_name_big;
            $img           = \ImageMaker::make($file->path());
            $img->fit(180);
            $img->save($tmp_big);
            $cos_file_info = QcloudUtils::uploadFile($tmp_big, $file_name_big, 'category');
            //上传到COS失败
            if (empty($cos_file_info) || $cos_file_info['code'] != 0) {
                return;
            }
            //save small logo
            $img->fit(32);
            $file_name_small = sprintf($file_name_formatter, 'logo.small');
            $tmp_small       = '/tmp/' . $file_name_small;
            $img->save($tmp_small);
            QcloudUtils::uploadFile($tmp_small, $file_name_small, 'category');
            $this->logo = $cos_file_info['data']['custom_url'];
        }

        if ($request->logo_app) {
            $file                = $request->logo_app;
            $extension           = $file->getClientOriginalExtension();
            $file_name_formatter = $name . '.%s.' . $extension;
            //save logo_app
            $file_name_big = sprintf($file_name_formatter, 'logo.app');
            $tmp_big       = '/tmp/' . $file_name_big;
            $img           = \ImageMaker::make($file->path());
            $img->fit(180);
            $img->save($tmp_big);
            $cos_file_info = QcloudUtils::uploadFile($tmp_big, $file_name_big, 'category');
            //上传到COS失败
            if (empty($cos_file_info) || $cos_file_info['code'] != 0) {
                return;
            }
            //save small logo_app
            $img->fit(32);
            $file_name_small = sprintf($file_name_formatter, 'logo.small.app');
            $tmp_small       = '/tmp/' . $file_name_small;
            $img->save($tmp_small);
            QcloudUtils::uploadFile($tmp_small, $file_name_small, 'test');
            $this->logo_app = $cos_file_info['data']['custom_url'];
        }
    }

    public function recordBrowserHistory()
    {
        //记录浏览历史
        if (checkUser()) {
            $user = getUser();
            //如果重复浏览只更新纪录的时间戳
            $visited = \App\Visit::firstOrNew([
                'user_id'      => $user->id,
                'visited_type' => 'categories',
                'visited_id'   => $this->id,
            ]);
            $visited->updated_at = now();
            $visited->save();
        }
    }

    public static function getTopCategory($number = 5)
    {
        $data             = [];
        $ten_top_category = Category::select(DB::raw('count(*) as categoryCount,category_id'))
            ->from('articles')
            ->where('type', 'video')
            ->whereNotNull('category_id')
            ->groupBy('category_id')
            ->orderBy('categoryCount', 'desc')
            ->take($number)->get()->toArray();

        foreach ($ten_top_category as $top_category) {
            $cate           = Category::find($top_category["category_id"]);
            $data['name'][] = $cate ? $cate->name : '空';
            $data['data'][] = $top_category["categoryCount"];
        }
        return $data;
    }

    public static function getTopLikeCategory($number = 5)
    {
        $data = [];

        $ten_top_category = Category::select(DB::raw('sum(count_likes) as categoryCount,category_id'))
            ->from('articles')
            ->where('type', 'video')
            ->whereNotNull('category_id')
            ->groupBy('category_id')
            ->orderBy('categoryCount', 'desc')
            ->take($number)->get()->toArray();

        foreach ($ten_top_category as $top_category) {
            $cate              = Category::find($top_category["category_id"]);
            $data['options'][] = $cate ? $cate->name : '空';
            $data['value'][]   = $top_category["categoryCount"];
        }
        return $data;
    }
}
