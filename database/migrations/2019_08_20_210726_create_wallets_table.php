<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('wallets')){
            return;
        }
        Schema::create('wallets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->unique()->comment('用户ID');
            $table->tinyInteger('type')->default(0)->comment('类型：默认RMB钱包');
            $table->string('pay_account', 60)->nullable()->comment('提现账号');
            $table->string('real_name', 50)->nullable()->comment('真实姓名 支持10个中文符号 此处50个长度方便扩展');
            $table->json('pay_infos')->nullable()->comment('提现信息');
            //冗余字段
            $table->unsignedInteger('total_withdraw_amount')->default(0)->comment('提现总额');
            
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
