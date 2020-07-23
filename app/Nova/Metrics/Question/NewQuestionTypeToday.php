<?php

namespace Haxibiao\Question\Nova\Metrics\Question;

use Haxibiao\Question\Question;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Partition;

class NewQuestionTypeToday extends Partition
{
    public $name = '每日新题类型';

    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        $questionTypes = Question::selectRaw('type,count(*) as count')
            ->where('created_at', '>=', today())
            ->groupBy('type')
            ->get();

        $result = [];
        foreach ($questionTypes as $questionType) {
            $key = '';

            if ($questionType->type == Question::TEXT_TYPE) {
                $key = '文字题';
            } else if ($questionType->type == Question::IMAGE_TYPE) {
                $key = '图片题';
            } else if ($questionType->type == Question::VIDEO_TYPE) {
                $key = '视频题';
            } else {
                $key = '未知';
            }

            $result[$key] = $questionType->count;
        }

        return $this->result($result);
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'new-question-type-today';
    }
}
