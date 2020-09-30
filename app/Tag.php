<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Haxibiao\Tag\Tag as BaseTag;

class Tag extends BaseTag
{
    use \App\Traits\Searchable;

    protected $searchable = [
        'columns' => [
            'tags.name' => 1,
        ],
    ];

    // old relationship
    public function creator()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function articles():MorphToMany
    {
        return $this->taggable('App\Article');
    }

    public function videos():MorphToMany
    {
        return $this->taggable('App\Video');
    }

    public function posts():MorphToMany
    {
        return $this->taggable(\App\Post::class);
    }

    public function resolverPosts($rootValue, $args, $context, $resolveInfo){

        $visibility = data_get($args,'visibility');
        $order      = data_get($args,'order');
        $user = getUser(false);

        $qb = $rootValue->posts()->publish();

        $qb->when( $visibility == 'self' , function ($q) use ($user){
            $q->where('taggables.user_id',data_get($user,'id'));
        });

        $qb->when( $order == 'LATEST' , function ($q){
            $q->orderByDesc('id');
        });

        return $qb;
    }

    public function resolveSearchTags($rootValue, $args, $context, $resolveInfo){
        return Tag::search(data_get($args,'query'));
    }

    public function getCountPostsAttribute(){
        return $this->count;
    }

    public function getCountViewsAttribute(){
        $countViews = 0;
        $this->posts()->each(function ($post) use (&$countViews){
            $countViews += data_get($post,'video.json.count_views',0);
        });
        return numberToReadable($countViews);
    }
}
