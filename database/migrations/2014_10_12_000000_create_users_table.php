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
            $table->string('password')->nullable();

            $table->string('avatar')->nullable();
            $table->integer('status')->default(0)->index();
            $table->boolean('role_id')->default(0)->index()->comment("0用户，１签约作者，２：管理员");
            $table->unsignedInteger('ticket')->default(180)->comment('精力点');
            $table->unsignedInteger('gold')->default(0)->comment('墨宝');
            $table->unsignedInteger('dz_id')->nullable()->unique()->comment('答题赚钱ID');
            $table->string('account')->unique()->comment('账号')->nullable();
            $table->string('uuid')->nullable()->comment('用户设备唯一标识');
            $table->string('phone')->nullable()->comment('用户手机号');
            $table->unsignedInteger('age')->nullable();
            // $table->boolean('is_editor')->default(0)->index();
            // $table->boolean('is_admin')->default(0)->index();
            // $table->boolean('is_signed')->default(0)->index();  //是否签约作者
            // $table->boolean('is_seoer')->default(0)->index();

            // //profile
            // $table->string('qq')->nullable();
            // $table->string('introduction')->nullable();

            // //seo
            // $table->boolean('is_signed')->default(0)->index();  //是否签约作者
            // // $table->boolean('is_seoer')->default(0)->index();
            // $table->text('json')->nullable();

            // //count
            // $table->integer('count_articles')->default(0);
            // $table->integer('count_likes')->default(0);
            // $table->integer('count_follows')->default(0);
            // $table->integer('count_followings')->default(0);
            // $table->integer('count_words')->default(0);
            // $table->integer('count_collections')->default(0);
            // $table->integer('count_favorites')->default(0);
            // $table->integer('count_actions')->default(0);
            // $table->integer('count_reports')->default(0);

            // //profile
            // $table->boolean('enable_tips')->default(1);
            // $table->string('tip_words')->nullable();
            // $table->string('gender')->nullable()->index();
            // $table->string('website')->nullable();
            // $table->string('qrcode')->nullable();

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
