<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:data {--traffic} {--articles}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '--traffic: fix existing traffic date string, day of year etc ....';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if($this->option('traffic'))
            $this->fix_traffic();
        if($this->option('articles'))
            $this->fix_articles();
    }

    function fix_articles() {
        $articles = \App\Article::all();
        foreach($articles as $article) {
            $category = \App\Category::find($article->category_id);
            if(!$category) {
                $article->category_id = 1;
                $article->save();
                $this->info('fix category: '. $article->title);
            }
        }
    }

    function fix_traffic() {
        $traffics = \App\Traffic::all();
        foreach($traffics as $traffic) {
            $created_at = $traffic->created_at;

            $traffic->date = $created_at->format('Y-m-d');
            $traffic->year = $created_at->year;
            $traffic->month = $created_at->month;
            $traffic->day = $created_at->day;

            $traffic->dayOfWeek = $created_at->dayOfWeek;
            $traffic->dayOfYear = $created_at->dayOfYear;
            $traffic->daysInMonth = $created_at->daysInMonth;
            $traffic->weekOfMonth = $created_at->weekOfMonth;
            $traffic->weekOfYear = $created_at->weekOfYear;

            $traffic->save();

            $this->info($traffic->id);
        }
    }
}
