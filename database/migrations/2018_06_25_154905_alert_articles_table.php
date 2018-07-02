<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlertArticlesTable extends Migration
{ 
    /**
     * Run the migrations.
     *
     * @return void
     */ 
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            //如果该内容是视频则该字段不为空
            $table->unsignedInteger('video_id')
                ->nullable()
                ->index()->comment('视频id');
            
            //如果该内容是视频则该字段不为空
            $table->string('video_url')
                ->nullable()
                ->comment('视频的url');

            $table->string('type',10)
                ->default('article')
                ->index()
                ->comment('文章的类型:article(文章)，video(视频), post(动态).默认为articles');

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            //
        });
    }
}
