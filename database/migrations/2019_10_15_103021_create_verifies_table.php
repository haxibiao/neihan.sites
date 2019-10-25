<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerifiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('verifies')) {
            Schema::dropIfExists('verifies');
        }

        Schema::create('verifies', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('user_id')->nullable()->default(null)->comment('关联用户');
            $table->string('channel', 10)->comment('发送频道，例如 mail, sms');
            $table->string('account', 50)->comment('发送账户');
            $table->string('code', 30)->comment('发送验证码');
            $table->string('action')->nullable()->comments('行为');

            $table->softDeletes();

            $table->index('account');
            $table->index('user_id');

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
        Schema::dropIfExists('verifies');
    }
}
