<?php

namespace App\Console\Commands;

use App\User;
use App\UserRetention;
use App\Visit;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ReCountNovaData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recount:novadata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '统计Nova后台数据';

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
        //统计用户留存率
        User::with('visits')->chunk(100,function($users){
            foreach ($users as $user) {

                $registed_at = $user->created_at;
                if(!$registed_at){
                    continue;
                }
                //次日留存
                $next_day_retention_at = $registed_at->addDay(1);
                $is_next_day_retention = $user->visits()->whereDate('created_at',$next_day_retention_at)->count() > 0;
                if($is_next_day_retention){
                    $retention = UserRetention::firstOrNew([
                        'user_id' => $user->id
                    ]);
                    $retention->next_day_retention_at = $user->created_at;
                    $retention->save();
                }

                $registed_at = $user->created_at;
                //3日留存
                $third_day_retention_at = $registed_at->addDay(3);
                $is_third_day_retention = $user->visits()->whereDate('created_at',$third_day_retention_at)->count() > 0;
                if($is_third_day_retention){
                    $retention = UserRetention::firstOrNew([
                        'user_id' => $user->id
                    ]);
                    $retention->third_day_retention_at = $user->created_at;
                    $retention->save();
                }

                $registed_at = $user->created_at;
                //5日留存
                $fifth_day_retention_at = $registed_at->addDay(5);
                $is_fifth_day_retention = $user->visits()->whereDate('created_at',$fifth_day_retention_at)->count() > 0;
                if($is_fifth_day_retention){
                    $retention = UserRetention::firstOrNew([
                        'user_id' => $user->id
                    ]);
                    $retention->fifth_day_retention_at = $user->created_at;
                    $retention->save();
                }

                $registed_at = $user->created_at;
                //7日留存
                $sixth_day_retention_at = $registed_at->addDay(7);
                $is_sixth_day_retention = $user->visits()->whereDate('created_at',$sixth_day_retention_at)->count() > 0;
                if($is_sixth_day_retention){
                    $retention = UserRetention::firstOrNew([
                        'user_id' => $user->id
                    ]);
                    $retention->sixth_day_retention_at = $user->created_at;
                    $retention->save();
                }

                $registed_at = $user->created_at;
                //30日留存
                $month_retention_at = $registed_at->addDay(7);
                $is_month_retention = $user->visits()->whereDate('created_at',$month_retention_at)->count() > 0;
                if($is_month_retention){
                    $retention = UserRetention::firstOrNew([
                        'user_id' => $user->id
                    ]);
                    $retention->month_retention_at = $user->created_at;
                    $retention->save();
                }
            }
        });

        //统计昨日用户活跃数
        $cacheKey = 'nova_user_activity_num_of_%s';
        $key   = sprintf($cacheKey, Carbon::yesterday()->toDateString());
        $count  = Visit::whereDate('created_at',Carbon::yesterday())->distinct()->count('user_id');
        cache()->store('database')->forever($key, $count);
    }
}
