<?php

namespace App\Traits;

use App\Task;

trait AssignmentRepo
{
    //初始化用户的任务指派
    public static function initAssignments($user)
    {
        $task_ids          = Task::enabled()->pluck('id')->toArray();
        $assigned_task_ids = $user->assignments()->pluck('task_id')->toArray();
        $needSyncTasks     = count(array_diff($task_ids, $assigned_task_ids)) ||
        count(array_diff($assigned_task_ids, $task_ids));

        if ($needSyncTasks) {
            //指派所有可指派的任务,更新任务列表，符合当前任务系统版本要求
            $user->tasks()->sync($task_ids);
        }
    }

    /**
     * 获取任务流的执行结果
     *
     * @return bool
     */
    public function getTaskFlowsResult()
    {

        $flows = $this->review_info['flows'];
        foreach ($flows as $flow) {

            $result = $this->$flow();
            // 排队执行flows。单个flow执行失败整个flows失败。
            if (!$result) {
                return false;
            }
        }
        return true;
    }

}
