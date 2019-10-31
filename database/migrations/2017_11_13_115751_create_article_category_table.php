<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_category', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('article_id');
            $table->integer('category_id');

            //文章在专题内的删除状态...
            $table->string('status')->nullable()->index()->comment('文章在专题的状态，-1删除，null或0隐藏，1上线');
            //投稿状态
            $table->string('submit')->nullable()->index()->comment('投稿状态：待审核，已收录，已拒绝');
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
        Schema::dropIfExists('article_category');
    }
}
