<?php

use App\FunctionSwitch;
use Illuminate\Database\Seeder;

class FunctionSwitchSeeder extends Seeder
{
    public function run()
    {
        DB::table('function_switchs')->truncate();

        if (str_contains(env('APP_NAME'), ["dongqizhi", "dongshouji", "dongwaiyu", "dongyundong", "tongjiuxiu", "dongwuli", "dongdaima", "caohan", "donghuamu", "dongmiaomu", "gba-port", "gba-port"])) {
            FunctionSwitch::firstOrCreate([
                'name'          => '提现',
                'state'         => 0,
                'close_details' => '提现正在维护中，望请谅解',
            ]);
        } else {
            FunctionSwitch::firstOrCreate([
                'name'          => '提现',
                'state'         => 1,
                'close_details' => '提现正在维护中，望请谅解',
            ]);
        }

    }
}
