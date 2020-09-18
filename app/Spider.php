<?php

namespace App;

use Haxibiao\Media\Spider as BaseSpider;

class Spider extends BaseSpider
{
    public function resolveShareLink($root, $args, $context, $info)
    {

        $spider = parent::resolveDouyinVideo(getUser(), $args['share_link']);
        $post = Post::with('video')->firstOrNew(['spider_id' => $spider->id]);

        $content = data_get($args, 'content');
        if ($content) {
            $post->content          = $content;
        }

        $description = data_get($args, 'description');
        if ($description) {
            $post->description      = $description;
        }
        // 标签
        $tagNames = data_get($args, 'tag_names', []);
        $post->tagByNames($tagNames);
        $post->save();

        return $spider;
    }
}
