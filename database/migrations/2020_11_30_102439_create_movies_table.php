<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // Schema::create('movies', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name', 100)->index()->comment("电影名");
        //     $table->string('introduction', 255)->index()->comment("简介");
        //     $table->string('cover', 200)->index()->comment("封面");
        //     $table->string('producer', 100)->index()->comment("导演");
        //     $table->string('year', 100)->index()->comment("年份");
        //     $table->string('type', 100)->index()->comment("分类");
        //     $table->string('style', 100)->index()->comment("风格");
        //     $table->string('region', 100)->index()->comment("地区");
        //     $table->integer('count_series')->default(0)->comment("总集数");
        //     $table->string('actors', 100)->index()->comment("演员");
        //     $table->json('data')->comment('剧集播放数据');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
    }
}
