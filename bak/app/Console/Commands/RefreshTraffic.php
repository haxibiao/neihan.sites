<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefreshTraffic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:traffic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fix existing traffic date string, day of year etc ....';

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
