<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrafficTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('traffic', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('is_desktop')->default(0)->index();
            $table->boolean('is_mobile')->default(0)->index();
            $table->boolean('is_phone')->default(0)->index();
            $table->boolean('is_tablet')->default(0)->index();
            $table->boolean('is_wechat')->default(0)->index();
            $table->boolean('is_android_os')->default(0)->index();
            $table->boolean('is_robot')->default(0)->index();

            $table->string('device')->nullable()->index();
            $table->string('platform')->nullable()->index();
            $table->string('browser')->nullable()->index();
            $table->string('robot')->nullable()->index();

            $table->string('ip')->nullable()->index();
            $table->string('country')->nullable()->index();
            $table->string('province')->nullable()->index();
            $table->string('city')->nullable()->index();

            $table->integer('user_id')->nullable()->index();

            $table->integer('article_id')->nullable();
            $table->string('category')->nullable();

            $table->string('date')->nullable()->index();
            $table->string('year')->nullable()->index();
            $table->string('month')->nullable()->index();
            $table->string('day')->nullable()->index();
            $table->string('dayOfWeek')->nullable()->index();
            $table->string('dayOfYear')->nullable()->index();
            $table->string('daysInMonth')->nullable()->index();
            $table->string('weekOfMonth')->nullable()->index();
            $table->string('weekOfYear')->nullable()->index();

            $table->string('referer', 2083)->nullable()->change();

            $table->string('path')->nullable();
            $table->string('referer')->nullable();
            $table->string('referer_domain')->nullable();
            
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
        Schema::dropIfExists('traffic');
    }
}
