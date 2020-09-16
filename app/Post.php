<?php

namespace App;

use Haxibiao\Content\Post as BasePost;
use Haxibiao\Media\Spider;
use Illuminate\Support\Arr;

class Post extends BasePost
{
    use \App\Traits\Searchable;
    use \App\Traits\CanBeTaged;
    use \App\Traits\CanBeLiked;

    protected $searchable = [
        'columns' => [
            'posts.description' => 2,
            'taggables.tag_name' =>1
        ],
        'joins' => [
            'taggables' => [
                ['taggables.taggable_id', 'posts.id'],
                ['taggables.taggable_type', 'posts'],
            ],
        ],
    ];

    public function resolveSearchPosts($root, array $args, $context){
        $userId = data_get($args,'user_id');
        $tagId  = data_get($args,'tag_id');
        $type  = data_get($args,'type');
        return static::publish()->search(data_get($args,'query'))
            ->when($type == 'VIDEO', function ($q) use ($userId){
                return $q->whereNotNull('video_id');
            })->when($type == 'IMAGE', function ($q) use ($userId){
                return $q->whereNull('video_id');
            })->when($userId, function ($q) use ($userId){
                return $q->where('posts.user_id',$userId);
            })->when($tagId, function ($q) use ($tagId){
                return $q->whereHas('tags',function($q) use ($tagId) {
                    $q->where('tags.id', $tagId);
                });
            })->with('video');
    }

    public function resolveUserPosts($root, $args, $context, $info)
    {
        $filter = data_get($args,'filter');

        if($filter == 'spider'){
            return static::posts($args['user_id'])->whereNotNull('spider_id');
        } elseif($filter == 'normal') {
            return static::posts($args['user_id'])->whereNull('spider_id');
        }
        return static::posts($args['user_id']);
    }

    public function resolveUpdatePost($root, $args, $context, $info){
        $postId = data_get($args,'post_id');
        $post = static::findOrFail($postId);
        $post->update(
            Arr::only($args, ['content', 'description'])
        );

        // 同步标签
        $tagNames = data_get($args,'tag_names',[]);
        $post->retagByNames($tagNames);

        return $post;
    }
}
