<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlertVideoStep2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            // if (Schema::hasColumn('videos', 'hits')) {
            //     $table->dropColumn([
            //         'hits',
            //         'category_id',
            //         'introduction',
            //         'path_mp4',
            //         'count',
            //         'likes',
            //         'cover',
            //     ]);
            // }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {
            //
        });
    }
}
