<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('withdraws')){
            return;
        }
        Schema::create('withdraws', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('wallet_id')->index()->comment('钱包ID');
            $table->tinyInteger('status')->default('0')->comment('待处理(0) 提现成功(1) 提现失败(-1)');
            $table->unsignedInteger('transaction_id')->nullable()->comment('交易ID');
            $table->decimal('amount', 10, 2)->comment('金额');
            $table->string('remark')->nullable()->comment('回执信息');
            $table->string('trade_no')->nullable()->comment('第三方交易单号');
            $table->string('to_account')->nullable()->comment('提现账户');
            $table->string('to_platform')->nullable()->comment('支付平台');
            $table->unsignedInteger('rate')->nullable()->index()->comment('倍率');
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
        Schema::dropIfExists('withdraws');
    }
}
