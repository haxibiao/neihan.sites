<?php

namespace App\Jobs;

use App\Notifications\PlatformAccountExpire;
use App\Order;
use App\PlatformAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

//订单自动过期
class OrderAutoExpire implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    protected $account;
    protected $order;

    /**
     *
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PlatformAccount $account, Order $order)
    {
        $this->account = $account;
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $account = $this->account;
        $order = $this->order;
        if ($account) {
            //更新账号状态：已到期
            // dd($account);
            $account->update([
                'order_status' => \App\PlatformAccount::EXPIRE,
            ]);
            //更新订单状态：已过期
            $order->update(["status" => \App\Order::EXPIRE]);
            // dd($account->user());
            $user = $account->user;
            // dd($user);
            $user->notify(new PlatformAccountExpire($account));
            //通知商家修改密码

        }
    }
}
