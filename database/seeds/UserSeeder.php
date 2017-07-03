<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrNew([
            'email' => 'author_test@haxibiao.com',
        ]);
        $user->name      = '江湖小郎中';
        $user->qq        = '258212404';
        $user->password  = bcrypt('mm1122');
        $user->introduction = '我就是那个懂点医就浪迹江湖的小郎中...';
        $user->is_admin  = 1;
        $user->is_editor = 1;
        $user->save();

        $user = User::firstOrNew([
            'email' => 'ivan@haxibiao.com',
        ]);
        $user->name      = '老张';
        $user->qq        = '258212404';
        $user->password  = bcrypt('mm1122');
        $user->introduction = '老张就是我...';
        $user->is_admin  = 0;
        $user->is_editor = 1;
        $user->save();
    }
}
