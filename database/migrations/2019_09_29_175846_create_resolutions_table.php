<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResolutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //TODO: 重命名为 solutions
        Schema::create('resolutions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('user_id');
            $table->unsignedInteger('issue_id');
            $table->unsignedInteger('article_id')->nullable();
            $table->string('image_url')->nullable();
            $table->longText('answer');

            $table->unsignedInteger('count_likes')->default(0);
            $table->unsignedInteger('count_unlikes')->default(0);
            $table->unsignedInteger('count_comments')->default(0);
            $table->unsignedInteger('count_reports')->default(0);

            $table->tinyInteger('status')->default(0)->index();

            //分得奖金
            $table->decimal('bonus')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('issue_id');
            $table->index('article_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resolutions');
    }
}
