<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('exchanges')) {
            return;
        }
        Schema::create('exchanges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->integer('gold')->comment('智慧点');
            $table->unsignedInteger('rmb')->comment('RMB');
            $table->unsignedInteger('exchange_rate')->comment('兑换比率');

            //TODO: 这个冗余弥补gold表的清理，留下关键兑换记录
            // transaction里应该关联一个transaction_id，无需记录gold_id（会清理）
            $table->unsignedInteger('gold_balance')->comment('智慧点余额');
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
        Schema::dropIfExists('exchanges');
    }
}
