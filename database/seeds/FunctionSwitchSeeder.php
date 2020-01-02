<?php

use App\FunctionSwitch;
use Illuminate\Database\Seeder;

class FunctionSwitchSeeder extends Seeder
{
    public function run()
    {
        DB::table('function_switchs')->truncate();
        $functionswitch = FunctionSwitch::firstOrNew([
            'name' => '提现',
        ]);
        if (str_contains(env('APP_NAME'), ["dongyundong", "tongjiuxiu", "dongdaima", "caohan", "dongmiaomu", "gba-port", "gba-port"])) {

            $functionswitch->state         = 0;
            $functionswitch->close_details = '提现正在维护中，望请谅解';
            $functionswitch->save();

        } else {
            $functionswitch->state         = 1;
            $functionswitch->close_details = '提现正在维护中，望请谅解';
            $functionswitch->save();
        }

    }
}
