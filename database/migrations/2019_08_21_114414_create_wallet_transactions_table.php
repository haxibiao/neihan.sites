<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('wallet_transactions')) {
            return;
        }
        //TODO: 这个表的职能，应该借助exchange表和withdraws表抗下，drop掉
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('wallet_id')->index()->comment('钱包ID');
            $table->decimal('amount', 10, 2)->default(0)->comment('变动金额');
            $table->decimal('balance', 10, 2)->default(0)->comment('余额');
            $table->string('remark')->default(0)->comment('备注');
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
        Schema::dropIfExists('wallet_transactions');
    }
}
