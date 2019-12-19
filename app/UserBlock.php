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

    public function userBlock(){
        return $this->belongsTo(\App\User::class,'user_block_id','id');
    }

    public function articleBlock(){
        return $this->belongsTo(\App\Article::class,'article_block_id','id');
    }

    public function articleReport(){
        return $this->belongsTo(\App\Article::class,'article_report_id','id');
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

     //添加用户对动态的不感兴趣
     public function addArticleBlock($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
     {
         $user = getUser();
         //跳过已经屏蔽过的用户
         $existUser = \App\UserBlock ::where("article_block_id",$args['id'])->get(); 
         if($existUser->count()){
             throw new GQLException('添加\'不感兴趣\'失败，您已经对该动态标记过\'不感兴趣\'');
         }    
         $articleBlock  = Article::find($args['id']);  
         if(empty($articleBlock)){
             throw new GQLException('添加\'不感兴趣\'失败，不存在该动态');
         }
         $ub = new UserBlock();
         $ub->user_id =  $user->id;
         $ub->article_block_id = $articleBlock->id;
         $ub->save();
         return $ub;
     }

       //举报Article,达到两个举报就下架
    public function reportArticle($rootValue, array $args, $context, $resolveInfo){
        
        $userReport = getUser();
        $article = Article::where("id",$args['id'])->where("status",1)->first();
        if(empty($article)){
            throw new GQLException("举报失败，该动态不存在或已被下架！");
        }

        $myReportCount = UserBlock::where("article_report_id",$args['id'])->where("user_id",$userReport->id)->count();
        if($myReportCount>0){
            throw new GQLException("您已经举报过该动态！");
        }
        \DB::beginTransaction();
        try{
            $ub = new UserBlock();
            $ub->user_id = $userReport->id;
            $ub->article_report_id = $args['id'];
            $ub->save();
     
            $ReportCount = UserBlock::where("article_report_id",$args['id'])->count();
             //举报成功下架，并扣除智慧点和贡献点
             if($ReportCount>=2){
                 $article->status = -1;
                 $user = $article->user;
                 //智慧点或贡献点不够   扣则置为0
                $gold =  \App\Gold::makeOutcome($user,$user->gold - \App\Gold::REWARD_GOLD < 0 ?
                                                                                    $user->gold :  \App\Gold::REWARD_GOLD , '动态被举报下架扣除');
                $gold->save();
                                            
                 $count = $user->profile->count_contributes - Contribute::REWARD_VIDEO_POST_AMOUNT < 0 ?
                                                                                    0 : $user->profile->count_contributes - Contribute::REWARD_VIDEO_POST_AMOUNT;
                      //更新profile表上的字段
                  Profile::where('id', $user->profile->id)->update(['count_contributes' => $count]);
     
                  $article->save();
              }
            \DB::commit();
        }catch(Exception $e){
            \DB::rollback();
            throw new GQLException("未知错误，程序员小哥紧急修复中。。。！");
        }
      
        return $ub;
 }
}
