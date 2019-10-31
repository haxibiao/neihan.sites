<?php

namespace App\Observers;

use App\Report;

class ReportObserver
{
    /**
     * Handle the report "created" event.
     *
     * @param  \App\Report  $report
     * @return void
     */
    public function created(Report $report)
    {
        if ($report->reportable instanceof \App\Article) {
            $article = $report->reportable;
            $article->count_reports += 1;
            $article->save();

        } else if ($report->reportable instanceof \App\Comment) {
            $comment = $report->reportable;
            $comment->count_reports += 1;
            $comment->save();
        } else if ($report->reportable instanceof \App\User) {
            $user = $report->reportable;
            $user->count_reports += 1;
            $user->save();
        }

    }

    /**
     * Handle the report "updated" event.
     *
     * @param  \App\Report  $report
     * @return void
     */
    public function updated(Report $report)
    {
        //
    }

    /**
     * Handle the report "deleted" event.
     *
     * @param  \App\Report  $report
     * @return void
     */
    public function deleted(Report $report)
    {
        if ($report->reportable instanceof \App\Article) {
            $article = $report->reportable;
            $article->count_reports -= 1;
            $article->save();
        } else if ($report->reportable instanceof \App\Comment) {
            $comment = $report->reportable;
            $comment->count_reports -= 1;
            $comment->save();
        } else if ($report->reportable instanceof \App\User) {
            $user = $report->reportable;
            $user->count_reports += 1;
            $user->save();
        }

    }

    /**
     * Handle the report "restored" event.
     *
     * @param  \App\Report  $report
     * @return void
     */
    public function restored(Report $report)
    {
        //
    }

    /**
     * Handle the report "force deleted" event.
     *
     * @param  \App\Report  $report
     * @return void
     */
    public function forceDeleted(Report $report)
    {
        //
    }
}
