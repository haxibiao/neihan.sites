<?php

use App\FunctionSwitch;
use Illuminate\Database\Seeder;

class FunctionSwitchSeeder extends Seeder
{
    public function run()
    {
        DB::table('function_switchs')->truncate();
        $functionswtich = FunctionSwitch::firstOrNew([
            'name' => '提现',
        ]);
        if (str_contains(env('APP_NAME'), ["dongqizhi", "dongshouji", "dongwaiyu", "dongyundong", "tongjiuxiu", "dongwuli", "dongdaima", "caohan", "donghuamu", "dongmiaomu", "gba-port", "gba-port"])) {
            $functionswtich->state         = 1;
            $functionswtich->close_details = '提现正在维护中，望请谅解';

        } else {
            $functionswtich->state         = 0;
            $functionswtich->close_details = '提现正在维护中，望请谅解';
        }
        $functionswtich->save();
    }
}
