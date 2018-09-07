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
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('password');

            $table->string('avatar')->nullable();
            $table->string('qq')->nullable();
            $table->string('introduction')->nullable();

            $table->boolean('is_editor')->default(0)->index();
            $table->boolean('is_admin')->default(0)->index();
            $table->integer('status')->default(0)->index();

            //seo
            $table->boolean('is_signed')->default(0)->index();  //是否签约作者
            // $table->boolean('is_seoer')->default(0)->index();
            $table->text('json')->nullable();

            //count
            $table->integer('count_articles')->default(0);
            $table->integer('count_likes')->default(0);
            $table->integer('count_follows')->default(0);
            $table->integer('count_followings')->default(0);
            $table->integer('count_words')->default(0);
            $table->integer('count_collections')->default(0);
            $table->integer('count_favorites')->default(0);
            $table->integer('count_actions')->default(0);
            $table->integer('count_reports')->default(0);

            //profile
            $table->boolean('enable_tips')->default(1);
            $table->string('tip_words')->nullable();
            $table->string('gender')->nullable()->index();
            $table->string('website')->nullable();
            $table->string('qrcode')->nullable();

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
