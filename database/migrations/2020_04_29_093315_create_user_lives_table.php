<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_lives', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->comment('主播 ID');
            $table->unsignedInteger('live_id')->comment('直播间 ID');
            $table->unsignedInteger('live_duration')->nullable()->comment('直播时长');
            $table->unsignedInteger('count_users')->nullable()->comment('总观看人数');
            $table->json('data')->nullable();

            $table->index(['user_id', 'live_id', 'live_duration']);
            // count_users 与 data 字段会常更新，不做索引
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
        Schema::dropIfExists('user_lives');
    }
}
