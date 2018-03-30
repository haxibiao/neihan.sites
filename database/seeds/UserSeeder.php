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
        $users = User::all();
        foreach ($users as $user) {
            $user->api_token = str_random(60);
            $user->avatar    = $user->avatar();
            $user->save();
        }

        $user_admin=User::where('name','汤圆er！')->first();

        $user_admin->is_admin=1;

        $user_admin->save();
    }
}
