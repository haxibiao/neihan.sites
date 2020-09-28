<?php
namespace App\Traits;

use App\Gold;
use App\Post;
use App\User;
use App\Wallet;
use App\Article;
use App\Profile;
use App\UserBlock;
use App\Contribute;
use Illuminate\Support\Arr;
use App\Exceptions\GQLException;
use Illuminate\Support\Facades\DB;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait UserBlockResolvers
{
      //添加用户黑名单（屏蔽用户）
      public function addUserBlock($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
      {
          if($user = checkUser()){
                  $userBlock  = User::find($args['id']);  
                  if(!$userBlock){
                      throw new GQLException('屏蔽失败，不存在该用户');
                  }
                 //跳过已经屏蔽过的用户
                  $existUser = \App\UserBlock ::where("user_id",$user->id)->where("user_block_id",$args['id'])->get(); 
                  if($existUser->count()){
                      throw new GQLException('屏蔽失败，您已经屏蔽过该用户');
                  }    
                  $ub = new UserBlock();
                  $ub->user_id =  $user->id;
                  $ub->user_block_id = $userBlock->id;
                  $ub->save();
                  return $ub;
          }
      }
  
       //添加用户对动态的不感兴趣
       public function addArticleBlock($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
       {
           if($user = checkUser()){
                  $articleBlock  = Post::find($args['id']);
                  if(empty($articleBlock)){
                      throw new GQLException('添加\'不感兴趣\'失败，不存在该动态');
                  }
                  //跳过已经屏蔽过的用户
                  $existUser = \App\UserBlock ::where("user_id",$user->id)->where("article_block_id",$args['id'])->get(); 
                  if($existUser->count()){
                      throw new GQLException('添加\'不感兴趣\'失败，您已经对该动态标记过\'不感兴趣\'');
                  }    
                  $ub = new UserBlock();
                  $ub->user_id =  $user->id;
                  $ub->article_block_id = $articleBlock->id;
                  $ub->save();
                  return $ub;
           }
       }
  
         //举报Article,达到两个举报就下架
      public function reportArticle($rootValue, array $args, $context, $resolveInfo){
          
          $userReport = getUser();
          $post = Post::publish()->find($args['id']);
          //$article = Article::where("id",$args['id'])->where("status",1)->first();
          if(empty($post)){
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
                   $post->status = -1;
                   $user = $post->user;
                   //智慧点或贡献点不够   扣则置为0
                  $gold =  \App\Gold::makeOutcome($user,$user->gold - \App\Gold::REWARD_GOLD < 0 ?
                                                                                      $user->gold :  \App\Gold::REWARD_GOLD , '动态被举报下架扣除');
                  $gold->save();
                                              
                   $count = $user->profile->count_contributes - Contribute::REWARD_VIDEO_POST_AMOUNT < 0 ?
                                                                                      0 : $user->profile->count_contributes - Contribute::REWARD_VIDEO_POST_AMOUNT;
                        //更新profile表上的字段
                   Profile::where('id', $user->profile->id)->update(['count_contributes' => $count]);

                   $post->save();
                }
              \DB::commit();
          }catch(Exception $e){
              \DB::rollback();
              throw new GQLException("未知错误，程序员小哥紧急修复中。。。！");
          }
          return $ub;
   }
  
     //用户‘我的黑名单’列表
     public function showUserBlock($rootValue, array $args, $context, $resolveInfo){
            $ids_ordered = "";
            return User::whereIn("id",function($query) use ($args,&$ids_ordered){
                 $ids = $query->select("user_block_id")->from("user_blocks")->whereNotNull("user_block_id")->
                 where("user_id",$args["user_id"])->orderBy("created_at","desc")->pluck("user_block_id")->toArray();
                 $ids_ordered = implode(',', $ids);
            })->orderByRaw(DB::raw("FIELD(id, $ids_ordered)"));
      }
  
      //移除‘我的黑名单’用户
      public function removeUserBlock($rootValue, array $args, $context, $resolveInfo){
            if($user=checkUser()){
                 return (UserBlock::where("user_id",$user->id)->where("user_block_id",$args["id"])->delete());
            }
      }
}
