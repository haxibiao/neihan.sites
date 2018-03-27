<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\Category;
use App\Http\Controllers\Controller;
use App\Image;
use Illuminate\Http\Request;

class CrawlController extends Controller
{

    protected $type = "haxibiao-crawl";

    public function getCrawl(Request $request)
    {
        $header = $request->header();

        if (!empty($request->all())) {

            $article = new Article($request->all());

            $article->save();

            $category = Category::findOrFail($article->category_id);

            $article->categories()->syncWithoutDetaching($article->category_id);

            $content = $article->body;

            $preg = "/<[img|IMG].*?src=['|\"](.*?(?:[.gif|.jpg]))['|\"].*?[\/]?>/"; 

            preg_match_all($preg, $content, $match);

            if (!empty($match[1])) {
                foreach ($match[1] as $index => $image_url) {
                    $image        = new Image();
                    $image->title = $article->title;
                    $image->save();

                    $dir  = '/storage/img/' . $image->id . '.jpg';
                    $path = public_path($dir);
                    file_put_contents($path, @file_get_contents($image_url));

                    $image_item = \ImageMaker::make($path);

                    //save small image
                    if ($image_item) {
                        //save small
                        if ($image_item->width() / $image_item->height() < 1.5) {
                            $image_item->resize(300, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        } else {
                            $image_item->resize(null, 240, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        }
                        $image_item->crop(300, 240);

                        $small_path = '/storage/img/' . $image->id . '.small' . '.jpg';

                        $image_item->save(public_path($small_path));

                        $image->path_small = $small_path;

                    }

                    $image->path = $dir;

                    $image->save();

                    if ($index == 1 && !empty($image->path_small)) {
                        $article->image_url = $image->path_small;
                    }

                    $body          = $article->body;
                    $body          = str_replace($image_url, $dir, $body);
                    $article->body = $body;

                    $article->save();

                    //save article_image relationships
                    $article->images()->syncWithoutDetaching($image->id);
                }
            }

            return ['success' . $article->id . $article->title];
        } else {
            return 'empty request body';
        }
    }

    /*  public function save_image($article)
{

$content=$article->body;

preg_match_all($preg, $content, $match);

if (!empty($match[1])) {
foreach ($match[1] as $index => $image_url) {
$image        = new Image();
$image->title = $article->title;
$image->save();

$dir  = '/storage/img/' . $image->id . '.jpg';
$path = public_path($dir);
file_put_contents($path, @file_get_contents($image_url));

$image_item = \ImageMaker::make($path);

//save small image
if ($image_item) {
//save small
if ($image_item->width() / $image_item->height() < 1.5) {
$image_item->resize(300, null, function ($constraint) {
$constraint->aspectRatio();
});
} else {
$image_item->resize(null, 240, function ($constraint) {
$constraint->aspectRatio();
});
}
$image_item->crop(300, 240);

$small_path = '/storage/img/' . $image->id . '.small' . '.jpg';

$image_item->save(public_path($small_path));

$image->path_small = $small_path;

}

$image->path = $dir;

$image->save();

if ($index == 1 && !empty($image->path_small)) {
$article->image_url = $image->path_small;
}

$body          = $article->body;
$body          = str_replace($image_url, $dir, $body);
$article->body = $body;

$article->save();

//save article_image relationships
$article->images()->syncWithoutDetaching($image->id);
}
}
}
 */
}
