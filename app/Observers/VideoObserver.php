<?php

namespace App\Observers;

use App\Video;

class VideoObserver
{
    /**
     * Handle the video "created" event.
     *
     * @param  \App\Video  $video
     * @return void
     */
    public function created(Video $video)
    {
        //
    }

    /**
     * Handle the video "updated" event.
     *
     * @param  \App\Video  $video
     * @return void
     */
    public function updated(Video $video)
    {
        //当视频被软删除 FIXME: 应该用下面的deleted事件，结合softDeletes
        if ($video->status == -1) {
            if ($article = $video->article) {

                //下面的是旧代码，Video作为资源表，无需触发太多逻辑

                // //软删除 article
                // $article->status = -1;
                // //更新article表上冗余的主分类
                // $article->category_id = null;
                // $article->save(['timestamps' => false]);

                // //删除分类关系
                // $categories = $article->categories;
                // $article->categories()->detach();

                // //减分类视频数
                // foreach ($categories as $category) {
                //     $category->count_videos = $category->count_videos - 1;
                //     $category->save();
                // }
            }
        }
    }

    /**
     * Handle the video "deleted" event.
     *
     * @param  \App\Video  $video
     * @return void
     */
    public function deleted(Video $video)
    {
        //
    }

    /**
     * Handle the video "restored" event.
     *
     * @param  \App\Video  $video
     * @return void
     */
    public function restored(Video $video)
    {
        //
    }

    /**
     * Handle the video "force deleted" event.
     *
     * @param  \App\Video  $video
     * @return void
     */
    public function forceDeleted(Video $video)
    {
        //
    }
}
