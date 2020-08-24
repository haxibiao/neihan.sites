<?php

/**
 * @Author  guowei<gongguowei01@gmail.com>
 * @Data    2020/4/26
 * @Version
 */

namespace App\Traits;

use App\Assignment;

trait AssignmentAttrsCache
{
    /**
     * 获取当前任务的详细进度
     *
     * @return mixed
     */
    public function getProgressCache()
    {
        return $this->getAttributes()['progress'];
    }

    /**
     * 获取任务的状态（中文）
     * @return mixed|string
     */
    public function getSubmitNameCache()
    {
        return Assignment::getTypes()[$this->status];
    }
}
