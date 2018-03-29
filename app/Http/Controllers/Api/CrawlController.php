<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\Category;
use App\Http\Controllers\Controller;
use App\Image;
use Illuminate\Http\Request;

class CrawlController extends Controller
{
    public function getCrawl(Request $request)
    {
        if (!empty($request->all())) {
            $article = Article::firstOrNew([
                'source_url' => $request->source_url,
            ]);

            $user_id          = rand(44, 143);
            $article->title   = $request->title;
            $article->body    = $request->body;
            $article->status  = 1;
            $article->user_id = $user_id;

            $article->save();

            // category relations
            $categories = Category::whereIn('name', [
                $request->category_name,
            ])->get()
            ;

            $category = $categories->random();

            $article->categories()->syncWithoutDetaching($category->category_id);

            $article->category_id = $category->id;

            if (!empty($article->images)) {
                return $article->images;
                $image_ids = [];
                foreach ($article->images as $image_url) {
                    $image = Image::firstOrNew([
                        'path' => $image_url,
                    ]);

                    $image->title = $article->title;
                    $image->path  = $image_url;
                    $image->save();

                    $image_ids[] = $image->id;
                }
                $article->images()->syncWithoutDetaching($image->id);
            }

            DB::table('article_category')->where('article_id', $article_item->id)->update(['submit' => '已收录']);

            $article->save();
            return ['success' . $article->id . $article->title];
        } else {
            return 'empty request body';
        }
    }

}
