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
        //删除荣誉的admin新账户
        $admin_email = env('MAIL_USERNAME');
        foreach (User::whereEmail($admin_email)->get() as $admin) {
            if ($admin->id > 1) {
                $admin->delete();
            }
        }

        //锁定id=1为super admin
        $user = User::find(1);
        if (!$user) {
            $user = User::firstOrNew([
                'email' => $admin_email,
            ]);
        }
        $user->email   = $admin_email;
        $user->account = $user->email;
        $user->phone   = $user->email;
        $user->name    = env('APP_NAME_CN');
        $pass          = env('MONGO_PASSWORD', 'REDIS_PASSWORD');

        $user->password   = bcrypt($pass);
        $avatar_formatter = 'https://cos.diudie.com/storage/avatar/avatar-%d.jpg';
        $user->avatar     = sprintf($avatar_formatter, rand(1, 15));
        $user->api_token  = str_random(60);
        $user->is_admin   = true;
        $user->is_editor  = true;
        $user->save();
        $profile = $user->profile;
        if (empty($profile)) {
            $profile          = new Profile();
            $profile->user_id = $user->id;
        }
        $profile->save();
    }
}
