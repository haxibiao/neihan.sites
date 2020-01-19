<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable()->index();
            $table->unsignedInteger('wallet_id')->nullable()->index();
            
            //TODO: morphs('tradable')
            $table->string('type')->index()->comment('账单类型：打赏, 充值，提现，奖励问答等');
            $table->integer('relate_id')->nullable()->comment('关联的对象id，配合type可以查询关联对象');

            $table->string('status')->default('未支付')->index()->comment('未支付，已到账');
            $table->string('remark')->nullable()->comment('交易备注');
            $table->decimal('amount');
            $table->decimal('balance')->default(0)->comment('账簿余额');

            $table->integer('from_user_id')->nullable()->comment('从用户');
            $table->integer('to_user_id')->nullable()->comment('到用户');

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
        Schema::dropIfExists('transactions');
    }
}
