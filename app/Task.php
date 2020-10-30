<?php

namespace App;

use Haxibiao\Task\Task as HXBTask;

class Task extends HXBTask
{
    //任务操作行为
    const VISIT_ACTION = 'visited';
    const LIKE_ACTION = 'liked';
    const COMMENT_ACTION = 'commented';


    //任务操作类
    const POST = 'posts';
    const COLLECTION = 'collections';
    const USER = 'users';
    const ARTICLE = 'articles';

    /**
     * 检查发布问题的数量
     */
    public function checkIssueCount($user, $task, $assignment)
    {
        $count = $assignment->current_count;
        return [
            'status'        => $count >= $task->max_count,
            'current_count' => $count,
        ];
    }

    /**
     * 检查回答问题的数量
     */
    public function checkSolutionCount($user, $task, $assignment)
    {
        $count = $assignment->current_count;
        return [
            'status'        => $count >= $task->max_count,
            'current_count' => $count,
        ];
    }

    public static function getActions()
    {
        return [
            self::LIKE_ACTION => '点赞',
            self::COMMENT_ACTION => '评论',
            self::VISIT_ACTION => '浏览',
        ];
    }
    public static function getActionClasses()
    {
        return [
            self::POST  => '动态',
            self::USER => '用户',
            self::COLLECTION => '集合',
        ];
    }

    public  function getCollectionAttribute()
    {
        if ($this->relation_class==self::COLLECTION){
            return  Collection::whereIn('id', $this->task_object)->first();
        }
        return null;
    }

}
