<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Chat;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::find(1);
        if ($user) {
            $chat = Chat::firstOrNew([
                'uids' => '[1,16]',
            ]);
            $chat->save();
            $with_users = [User::find(16)];

            $chats[$chat->id] = [
                'with_users' => json_encode($with_users),
            ];

            $chat = Chat::firstOrNew([
                'uids' => '[1,17]',
            ]);
            $chat->save();
            $with_users = [User::find(17)];

            $chats[$chat->id] = [
                'with_users' => json_encode($with_users),
            ];

            $user->chats()->sync($chats);

            //seed some message with user 16. 17

        }
    }
}
