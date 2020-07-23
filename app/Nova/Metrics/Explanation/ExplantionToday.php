<?php

namespace Haxibiao\Question\Nova\Metrics\Explanation;

use Haxibiao\Question\Explanation;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Partition;

class ExplantionToday extends Partition
{
    public $name = '每日解析';

    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        $explanationsToday = Explanation::selectRaw('type,count(*) as count')
            ->where('created_at', '>=', today())
            ->groupBy('type')
            ->get();
        $result = [];

        foreach ($explanationsToday as $explanationGroup) {
            $key = '';

            $type = $explanationGroup->type;

            if ($type == Explanation::TEXT_TYPE) {
                $key = '文字解析';
            } else if ($type == Explanation::IMAGE_TYPE) {
                $key = '图文解析';
            } else if ($type == Explanation::VIDEO_TYPE) {
                $key = '视频解析';
            } else if ($type == Explanation::MEDIA_TYPE) {
                $key = '多媒体解析';
            }

            $result[$key] = $explanationGroup->count;
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
        return 'explantion-today';
    }
}
