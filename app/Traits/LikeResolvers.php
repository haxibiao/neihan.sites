<?php

namespace App\Traits;

use App\Article;
use App\Like;
use App\Post;
use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait LikeResolvers
{

    public function resolveCreate($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $likedId     = data_get($args,'liked_id');
        $likedType   = data_get($args,'liked_type');

        $modelString = Relation::getMorphedModel($likedType);
        $model = $modelString::findOrFail($likedId);
        return $model->likeIt();
    }

    public function resolveToggleLike($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = getUser();
        $likedId     = data_get($args,'liked_id');
        $likedType   = data_get($args,'liked_type');
        $undo        = data_get($args,'undo',false);

        //处理由于前端乐观更新导致的id=-1
        if($likedId<0) {
            return null;
         }
        $modelString = Relation::getMorphedModel($likedType);
        $model = $modelString::findOrFail($likedId);
        if($undo){
            return $model->unLikeIt($user);
        }
        return $model->toggleLike($user);
    }

    public function resolveLikes($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user       = User::find($args['user_id']);
        if($user){
            return static::whereHasMorph(
                'likable',
                ['App\Post']
            )->where('user_id',$user->id)->orderBy('id', 'desc');
        }
    }
}
