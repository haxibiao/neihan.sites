<?php

namespace App\Listeners;

use App\Events\CommentWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Comment;
use App\User;
use App\Action;
use App\Notifications\ArticleCommented;

/**
 * 添加新评论
 */
class NewComment
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        // 
    }

    /**
     * Handle the event.
     *
     * @param  CommentWasCreated  $event
     * @return void
     */
    public function handle(CommentWasCreated $event)
    {
        //通知关联用户
        $this->notifyAssociatedUsers($event);
        //更新文章的冗余数据
        $this->updateArticleCount($event);
        //更新文章作者冗余数据
        //$this->updateUserCount($event);
        //记录操作日志
        $this->log($event);
    }
    /**
     * @Desc     通知相关用户
     * @Author   czg
     * @DateTime 2018-07-10
     * @return   [type]     [description]
     */
    protected function notifyAssociatedUsers($event){
        $comment = $event->comment;
        $article = $comment->commentable;

        //当前登录的用户 注意与GraphQL的统一性
        $authorizer = getUser();  
        //文章作者  
        $author     = $article->user;
        
        //作者与当前登录用户是否是同一人
        $authorizer_is_author = $author->id == $authorizer->id;
        //放入了用户 当前登录用户,文章作者，楼主 的ID
        $hidden_user_ids = [];
        $hidden_user_ids['authorizer'] = $authorizer->id;
        $hidden_user_ids['author']     = $author->id;

        //楼主
        if( !empty($comment->comment_id) ){
            $lord       = Comment::find($comment->comment_id)->user;
            $hidden_user_ids['lord'] = $lord->id;
        }
        //注释的原因：格式化link的操作全都放到model中去了
        //区分评论的"文章"类型
        // $type = '文章';
        // if($article->type == 'video'){
        //     $type = '视频';
        // } else if( $article->type == 'post' ){
        //     $type = '动态';
        // }

        //消息模板
        $msg_title_form1 = $authorizer->link() . ' 在' . $article->link() . '中写了一条新评论';
        $msg_title_form2 = $authorizer->link() . ' 评论了你的'  . $article->link();
        $msg_title_form3 = $authorizer->link() . ' 在' . $article->link() . '的评论中提到了你';

        //用户@人的逻辑。
        $filtered_users;//需要@的人
        //获取需要通知的用户id
        preg_match_all('/<at[^>]+data-id="(\d+)">[^>]+<\/at>/i', $comment->body, $comment_at_users);
        $comment_at_ids = end($comment_at_users);
        //该评论内容@了用户,但是用户输入的用户名不一定能对应到我们网站的用户,此处过滤
        $filtered_users = null;
        if( !empty($comment_at_ids) ){
            //整合 去重:避免@一个人多次，导致多次消息通知
            $comment_at_ids    = array_values($comment_at_ids);
            $comment_at_ids    = array_unique($comment_at_ids);

            //需要通知的用户
            $at_users = User::whereIn('id', $comment_at_ids)->get();
            $at_name_and_ids        = $at_users->pluck('id','name');
            if( $at_users->isNotEmpty() ){ 
                
                //@的用户都不属于以上三种身份(楼主，登录用户,作者)就发送艾特通知
                $filtered_users = $at_users->filter(function ($value, $key) use ( $hidden_user_ids ) {
                    return !in_array(
                        $value->id, $hidden_user_ids, true
                    );
                });

                //格式化消息通知的内容
                $user_names = $at_users->pluck('name')->toArray();
                $material_name = array_map(function($name){
                    return '@' . $name;
                }, $user_names);
                //拼接消息内容
                $format_at_users = [];
                foreach ($at_users as $user) {
                    $format_at_users[] = $user->at_link();
                }
                $commentBody = str_replace( 
                    $material_name, 
                    $format_at_users, 
                    $comment->body
                );

                //更新评论的内容，替换了超链接
                $comment->body      = $commentBody;
                $comment->save(['timestamps'=>false]);
            }
        }
        
        //直接回复文章
        if( empty($comment->comment_id) ){ 

            //登录用户与文章作者不是同一个人,只有文章作者收到通知。
            if( !$authorizer_is_author ){
                
                $author->forgetUnreads();

                $author->notify(new ArticleCommented(
                    $article, $comment, $authorizer, $msg_title_form2,$comment->body
                ));
            }
        //回复楼层
        } else {
            //楼主
            $authorizer_is_lord   = $lord->id == $authorizer->id;
            $lord_is_author       = $lord->id == $author->id;
            
            //登录用户,楼主,文章作者是同一人。没有通知。
            if( $authorizer_is_author && $lord_is_author ) {

            //作者、楼主是同一人 || 楼主、登录用户是同一人。只有文章作者收到通知。
            } else if ( $lord_is_author || $authorizer_is_lord ){
                $author->forgetUnreads();

                $author->notify( new ArticleCommented(
                    $article, $comment, $authorizer, $msg_title_form1, $comment->body
                ));

            //登录用户、作者是同一人。只有楼主收到通知。
            } else if ( $authorizer_is_author ) {
                $lord ->forgetUnreads();

                $lord->notify(new ArticleCommented(
                    $article, $comment, $authorizer, $msg_title_form1, $comment->body
                ));
                
            //登录用户,楼主,文章作者是不同的人。楼主与文章作者都收到通知。
            } else {
                
                $lord  ->forgetUnreads();
                $author->forgetUnreads();

                $lord->notify(new ArticleCommented(
                    $article, $comment, $authorizer, $msg_title_form1, $comment->body
                ));
                $author->notify( new ArticleCommented(
                    $article, $comment, $authorizer, $msg_title_form1, $comment->body
                ));

            }
        }
        //发送@人的消息通知
        if( !empty($filtered_users) ){    
            foreach ($filtered_users as $user) {
                $user->forgetUnreads();
                $user->notify( new ArticleCommented(
                    $article, $comment, $authorizer, $msg_title_form3, $comment->body 
                ));
            }
            //替换掉at标签
            $commentBody = str_replace(['<at', '</at>'], ['<a', '</a>'], $comment->body);
            $comment->body      = $commentBody;
            $comment->save(['timestamps'=>false]);
        }
    }
    /**
     * @Desc     更新文章的冗余数据
     * @Author   czg
     * @DateTime 2018-07-10
     * @param    [type]     $event [description]
     * @return   [type]            [description]
     */
    protected function updateArticleCount($event){
        $comment = $event->comment;
        $article = $comment->commentable;
        $article->count_replies  = $article->comments()->count();
        $article->count_comments = $article->comments()->max('lou');
        $article->commented      =  \Carbon\Carbon::now();
        $article->save();
    }
    /**
     * @Desc     更新文章作者的冗余信息
     * @Author   czg
     * @DateTime 2018-07-19
     * @return   [type]     [description]
     */
    public function updateUserCount($event){
        $comment = $event->comment;
        $article = $comment->commentable;
        $author  = $article->user;
        $author->increment('count_comments');
    }
    /**
     * @Desc     用户动态
     * @Author   czg
     * @DateTime 2018-07-10
     * @return   [type]     [description]
     */
    protected function log($event){
        $comment = $event->comment;
        $article = $comment->commentable;
        Action::create([
            'user_id'         => getUser()->id,
            'actionable_type' => 'comments',
            'actionable_id'   => $comment->id,
        ]);
    }
}
