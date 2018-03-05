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
        // $users = User::all();
        // foreach ($users as $user) {
        //     $user->api_token = str_random(60);
        //     $user->avatar    = $user->avatar();
        //     $user->save();
        // }

        $users = factory(User::class)->times(100)->make();
        User::insert($users->makeVisible(['password', 'remember_token'])->toArray());
    }
}
