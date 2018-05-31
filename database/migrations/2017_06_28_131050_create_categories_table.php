<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index();
            $table->string('type')->nullable(); //article, video
            $table->string('name');
            $table->string('name_en');
            $table->string('description')->nullable();
            $table->string('logo')->nullable();
            $table->integer('parent_id')->default(0);
            $table->boolean('has_child')->default(0);
            $table->integer('level')->nullable()->index();
            $table->integer('order')->nullable()->index();

            $table->integer('status')->default(0); // 0: disallow submit article, 1: allow
            $table->integer('request_status')->default(0); //0: submit request dont need approve, 1: need approve

            //counts
            $table->integer('count_follows')->default(0)->index();

            $table->integer('count')->default(0); //count_articles
            $table->integer('count_questions')->default(0);
            $table->integer('count_videos')->default(0);

            //新投稿数
            $table->integer('new_requests')->default(0);
            //新投稿标题
            $table->string('new_request_title')->nullable();

            //for app logo
            $table->boolean('is_official')->default(0);
            $table->boolean('is_for_app')->default(0);
            $table->string('logo_app')->nullable();

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
        Schema::dropIfExists('categories');
    }
}
