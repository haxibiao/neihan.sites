<?php
namespace Database\Seeders;

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
            'email' => env('MAIL_USERNAME'),
        ]);
        if ($user->id) {
            return;
        }
        $user->account    = $user->email;
        $user->phone      = $user->email;
        $user->name       = env('APP_NAME_CN');
        $user->password   = bcrypt(env(env('MONGO_PASSWORD', 'REDIS_PASSWORD')));
        $avatar_formatter = 'https://cos.diudie.com/storage/avatar/avatar-%d.jpg';
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
