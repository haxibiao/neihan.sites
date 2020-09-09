<?php

namespace App;

use Haxibiao\Task\Task as HXBTask;

class Task extends HXBTask
{
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
}
