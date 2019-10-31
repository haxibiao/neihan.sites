<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Report extends Model
{
    protected $guarded = [];

    const FAILED_STATUS  = -1;
    const REVIEW_STATUS  = 0;
    const SUCCESS_STATUS = 1;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reportable()
    {
        return $this->morphTo();
    }

    public static function getStatuses()
    {
        return [
            self::FAILED_STATUS  => '举报失败',
            self::REVIEW_STATUS  => '待审核',
            self::SUCCESS_STATUS => '举报成功',
        ];
    }

    public static function getReportTypes()
    {
        return [
            'comments' => '评论',
            'articles' => '动态|问答',
        ];
    }

    //resolvers
    public function store($root, array $args, $context)
    {
        $user                    = getUser();
        $report                  = new Report();
        $report->reason          = Arr::get($args, 'reason', '');
        $report->user_id         = $user->id;
        $report->reportable_id   = Arr::get($args, 'id');
        $report->reportable_type = Arr::get($args, 'type');
        $report->save();
        return $report;
    }
}