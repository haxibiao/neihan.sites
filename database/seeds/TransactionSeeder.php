<?php

use App\Transaction;
use App\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::where('is_editor', 1)->orderBy('id', 'desc')->get();
        foreach ($users as $user) {
            if ($user->balance() < 10) {
                $amount = 10;
                $log    = '帅气的peng向你的账户充值了' . $amount . '元';
                $type   = '充值';
                Transaction::create([
                    'user_id' => $user->id,
                    'type'    => $type,
                    'log'     => $log,
                    'amount'  => $amount,
                    'status'  => '已到账',
                    'balance' => $user->balance() + $amount,
                ]);
            }
        }
    }
}
