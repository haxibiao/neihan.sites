<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('gold')) {
            return;
        }
        //FIXME: 金币交易记录表，类似transactions表，但是这里只保留最近100条记录
        Schema::create('gold', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('wallet_id')->nullable()->index();
            $table->unsignedInteger('user_id')->index()->comment('用户ID');
            $table->integer('gold')->comment('智慧点');
            $table->integer('balance')->default(0)->comment('智慧点余额');
            $table->string('remark')->nullable()->comment('备注');
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
        Schema::dropIfExists('gold');
    }
}
