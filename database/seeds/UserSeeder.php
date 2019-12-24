<?php

use App\Profile;
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
        if($user->id){
            return;
        }
        $user->account    = $user->email;
        $user->phone      = $user->email;
        $user->roleId     = 2;
        $user->name       = '超级管理员';
        $user->password   = bcrypt('mm1122');
        $avatar_formatter = 'http://cos.' . env('APP_NAME') . '.com/storage/avatar/avatar-%d.jpg';
        $user->avatar     = sprintf($avatar_formatter, rand(1, 15));
        $user->api_token  = str_random(60);
        $user->save();
        $profile = $user->profile;
        if (empty($profile)) {
            $profile          = new Profile();
            $profile->user_id = $user->id;
        }
        $profile->save();
    }
}
