<?php

namespace App;

use App\User;
use App\Exceptions\GQLException;
use Illuminate\Database\Eloquent\Model;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UserBlock extends Model
{
    //
    public $fillable=[
            "user_id",
            "user_block_id",
    ];

    public function user(){
        return $this->belongsTo(\App\User::class);
    }

    public function userblock(){
        return $this->belongsTo(\App\User::class,'user_block_id','id');
    }

    //添加用户黑名单（屏蔽用户）
    public function addUserBlock($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = getUser();
        //跳过已经屏蔽过的用户
        $existUser = \App\UserBlock ::where("user_block_id",$args['id'])->get(); 
        if($existUser->count()){
            throw new GQLException('屏蔽失败，您已经屏蔽过该用户');
        }    
        $userBlock  = User::find($args['id']);  
        if(!$userBlock->count()){
            throw new GQLException('屏蔽失败，不存在该用户');
        }
        $ub = new UserBlock();
        $ub->user_id =  $user->id;
        $ub->user_block_id = $userBlock->id;
        $ub->save();
        return $ub;
    }
}
