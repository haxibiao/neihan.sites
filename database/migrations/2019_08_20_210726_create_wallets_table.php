<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('wallets')) {
            return;
        }
        Schema::create('wallets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->index()->comment('用户ID');
            $table->tinyInteger('type')->default(0)->comment('0:余额钱包,交易记录transactions，1:金币钱包,交易记录golds');

            //FIXME:  提现信息移动到profile ..
            $table->string('pay_account', 60)->nullable()->comment('支付宝提现账号');
            //TODO: 微信提现需要绑定另外一个 'wechat_account'
            $table->string('real_name', 50)->nullable()->comment('真实姓名 支持10个中文符号 此处50个长度方便扩展');

            $table->json('pay_infos')->nullable()->comment('提现信息');
            //TODO：冗余字段，drop,用属性算
            $table->decimal('total_withdraw_amount')->default(0)->comment('提现总额');

            $table->string('wechat_account')->default("")->comment('微信OPENID');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallets');
    }
}
