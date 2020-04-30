<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVideoIdToUserLives extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_lives', function (Blueprint $table) {
            if (!Schema::hasColumn('user_lives', 'video_id')) {
                $table->unsignedInteger('video_id')->comment('直播录制视频 ID')->nullable();
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
        Schema::table('user_lives', function (Blueprint $table) {
            //
        });
    }
}
