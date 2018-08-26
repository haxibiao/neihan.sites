<?php

namespace App\Listeners;

use App\Events\CommentWasDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DestroyedComment
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
     * @param  CommentWasDeleted  $event
     * @return void
     */
    public function handle(CommentWasDeleted $event)
    {   
        //删除用户动态
        $this->deleteAction($event);
        //清除消息通知
        $this->deleteCommentNotificationMsg($event);
        //更新文章的冗余数据
        $this->updateArticleCount($event);
        //更新作者的冗余信息
        //$this->updateUserCount($event);
    }
    /**
     * @Desc     删除用户动态
     * @Author   czg
     * @DateTime 2018-07-10
     * @param    [type]     $event [description]
     * @return   [type]            [description]
     */
    protected function deleteAction($event){
        $comment    = $event->comment;
        $user       = $comment->user;
        $article    = $comment->commentable;
        \App\Action::where([
            'user_id'         => $user->id,
            'actionable_type' => 'comments',
            'actionable_id'   => $article->id,
        ])->delete();
    }
    /**
     * @Desc     TODO 删除评论通知 数据库结构存在不足 需要编辑JSON结构如果数据量大的话
     * @Author   czg
     * @DateTime 2018-07-10
     * @param    [type]     $event [description]
     * @return   [type]            [description]
     */
    protected function deleteCommentNotificationMsg($event){
        
    }
    /**
     * @Desc     更新文章作者的冗余信息,评论自己也算
     * @Author   czg
     * @DateTime 2018-07-10
     * @param    [type]     $event [description]
     * @return   [type]            [description]
     */
    protected function updateUserCount($event){
        $comment = $event->comment;
        $article = $comment->commentable;
        $author  = $article->user;
        $author->decrement('count_comments');
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
        $article->save();
    }
}
