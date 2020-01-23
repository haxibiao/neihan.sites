<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('account')->unique()->comment('账号')->nullable();
            $table->string('uuid')->nullable()->comment('用户设备唯一标识');
            $table->string('phone')->nullable()->comment('用户手机号');

            $table->string('password')->nullable();
            $table->string('avatar')->nullable();
            $table->integer('status')->default(0)->index()->comment('0:默认，-1:异常');
            $table->boolean('role_id')->default(0)->index()->comment("0用户，１签约作者，２：管理员");

            $table->unsignedInteger('ticket')->default(180)->comment('精力点');
            $table->unsignedInteger('gold')->default(0)->comment('墨宝');
            $table->unsignedInteger('dz_id')->nullable()->unique()->comment('答题赚钱ID');

            //token
            $table->string('api_token', 60)->unique();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
