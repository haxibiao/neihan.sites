<?php

namespace App;

use Haxibiao\Content\Post as BasePost;
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
        $collectionId  = data_get($args,'collection_id');
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
            })->when($collectionId, function ($q) use ($collectionId){
                return $q->whereHas('collections',function($q) use ($collectionId) {
                    $q->where('collections.id', $collectionId);
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

    //关注用户的收藏列表
    public function resolveFollowPosts($rootValue, array $args, $context, $resolveInfo)
    {
        $filter = data_get($args,'filter');
        $user = getUser();
        //2.获取用户关注列表
        $followedUserIds = $user->follows() ->pluck('followed_id');
        //3.获取关注用户发布的视频
        $qb = static::whereNotNull('video_id')
            ->whereIn('user_id', $followedUserIds)
            ->orderByDesc('id');

        if(in_array(
            ['video','collections','images'],
            data_get($resolveInfo->getFieldSelection(1),'data')
        )){
            $qb->with(['video','collections','images']);
        }

        if($filter == 'spider'){
            return $qb->whereNotNull('spider_id');
        } elseif($filter == 'normal') {
            return $qb->whereNull('spider_id');
        }
        return $qb;
    }

    public function postByVideoId($rootValue, array $args, $context, $resolveInfo){
        $videoId = data_get($args,'video_id');
        return \App\Post::where('video_id',$videoId)->first();
    }
}
