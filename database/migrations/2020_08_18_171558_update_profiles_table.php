<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('profiles', 'today_reward_video_count')) {
                $table->unsignedInteger('today_reward_video_count')->default(0)->comment('今日激励视频次数');
            }
            if (!Schema::hasColumn('profiles', 'last_reward_video_time')) {
                $table->timestamp('last_reward_video_time')->nullable()->comment('最后激励视频时间');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            //
        });
    }
}
