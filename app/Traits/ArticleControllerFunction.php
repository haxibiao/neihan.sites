<?php

namespace App\Traits;

use App\Article;
use App\Category;
use App\Image;
use App\Jobs\ArticleDelay;
use App\Tag;
use App\Traits\ArticleControllerFunction;
use App\Video;
use Auth;
use Illuminate\Support\Facades\DB;

trait ArticleControllerFunction
{
    public function article_count($category_ids, $article)
    {
        if ($article->status > 0) {
            //update category article_count
            foreach ($category_ids as $category_id) {
                $category        = Category::find($category_id);
                $category->count = $category->articles()->count();
                $category->save();
            }
        }
    }

    public function article_coment_count($article)
    {
        $article->count_replies = $article->comments()->count();
        $article->count_likes   = $article->likes()->count();
        $article->save();
    }

    public function article_user_count($word)
    {
        $user = Auth::user();
        $user->count_articles++;
        $user->count_words = $user->count_words + $word;
        $user->save();
    }

    public function save_article_videos($request, $article)
    {
        $videos = $request->get('videos');
        if (is_array($videos)) {
            foreach ($videos as $video_id) {
                if (!is_numeric($video_id)) {
                    continue;
                }
                $video = Video::find($video_id);
                if ($video) {
                    $video->count = $video->count + 1;
                    $video->title = $article->title;
                    $video->save();

                }
                // $article->videos()->attach($video);
            }
        }
        $article->videos()->sync($videos);
    }

    public function fix_body($body)
    {
        $body = str_replace("\n", '<br/>', $body);
        $body = str_replace(' style=""', '', $body);
        $body = str_replace('class="box-related-full"', "", $body);
        $body = str_replace('class="box-related-half"', "", $body);
        return $body;
    }

    public function clear_article_imgs($article)
    {
        $pattern_img = '/<img(.*?)>/';
        preg_match_all($pattern_img, $article->body, $matches);
        if (!empty($matches)) {
            foreach ($article->images as $image) {
                $item_exist_in_body = false;
                foreach ($matches[0] as $img_tag) {
                    if (str_contains($img_tag, $image->path)) {
                        $item_exist_in_body = true;
                    }
                }
                if (!$item_exist_in_body) {
                    $article->images()->detach($image);
                    $image->count = $image->count - 1;
                    $image->save();
                }
            }
        }
    }

    public function auto_upadte_image_relations($imgs, $article)
    {
        if (!is_array($imgs)) {
            return;
        }

        $has_primary_top = false;
        $img_ids         = [];
        foreach ($imgs as $img) {
            $path      = parse_url($img)['path'];
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $path      = str_replace('.small.' . $extension, '', $path);
            if (str_contains($img, 'base64') || str_contains($path, 'storage/video')) {
                continue;
            }
            $image = Image::firstOrNew([
                'path' => $path,
            ]);
            if ($image->id) {
                $image->count = $image->count + 1;
                $image->title = $article->title;
                $image->save();

                $img_ids[] = $image->id;
                //auto get is_top an image_top
                if ($image->path_top) {
                    // $article->is_top    = 1;
                    if (!$has_primary_top) {
                        if ($image->path_small == $article->image_url) {
                            $has_primary_top = true;
                        }
                        $article->image_top = $image->path_top;
                        $article->save();
                    }
                }
                // $article->images()->attach($image);
            }
        }
        $article->images()->sync($img_ids);
    }

    public function save_article_tags($article)
    {
        $keywords = preg_split("/(#|:|,|，|\s)/", $article->keywords);
        foreach ($keywords as $word) {
            $word = trim($word);
            if (!empty($word)) {
                $tag = Tag::firstOrNew([
                    'name' => $word,
                ]);
                $tag->user_id = Auth::user()->id;
                $tag->save();
                $tag_ids[] = $tag->id;

            }
        }
        $article->tags()->sync($tag_ids);

    }

    public function get_json_lists($article)
    {
        $lists = json_decode($article->json, true);
        // return $lists;
        $lists_new = [];
        if (is_array($lists)) {
            foreach ($lists as $key => $data) {
                if (!is_array($data)) {
                    $data = [];
                }
                $items = [];
                if (!empty($data['aids']) && is_array($data['aids'])) {
                    foreach ($data['aids'] as $aid) {
                        $article = Article::find($aid);
                        if ($article) {
                            $items[] = [
                                'id'        => $article->id,
                                'title'     => $article->title,
                                'image_url' => get_img($article->image_url),
                            ];
                        }
                    }
                }
                if (!empty($items)) {
                    $data['items']   = $items;
                    $lists_new[$key] = $data;
                }
            }
        }
        return $lists_new;
    }
    //这里取出了全部的article模型返回到视图
    public function article_new()
    {
        $articles = Article::orderBy('id', 'desc')->paginate(10);
        return view('article.parts.article_new')->withArticles($articles);
    }

    public function article_is_top($request, $article)
    {
        if ($request->is_top) {
            $images = Image::where('path', $article->image_url)->orWhere('path_small', $article->image_url)->get();
            $is_top = 0;
            foreach ($images as $image) {
                if ($image->width < 760) {
                    continue;
                } else {
                    $is_top = 1;
                }
            }
            if ($is_top == 0) {
                dd("上传图片太小不能上首页!");
            }
            $article->save();
        }
    }

    public function get_image_urls_from_body($body)
    {
        $images           = [];
        $pattern_img_path = '/src=\"(\/storage\/img\/\d+\.(jpg|gif|png|jpeg))\"/';
        if (preg_match_all($pattern_img_path, $body, $match)) {
            $images = $match[1];
        }
        return $images;
    }

    public function article_delay($request, $article)
    {
        if ($request->delay > 0) {
            $article->status     = 0;
            $article->delay_time = now()->addHours($request->delay);
            $article->save();

            ArticleDelay::dispatch($article->id)
                ->delay(now()->addHours($request->delay));
        }
    }

    public function save_article_music($request, $article)
    {
        $music_id = $request->music_id;
        if (!empty($music_id)) {
            $article->music()->syncWithoutDetaching($music_id);
        }
    }

    public function category_article_submit($request, $article)
    {
        DB::table('article_category')->where('article_id', $article->id)->update(['submit' => '已收录']);
    }
}
