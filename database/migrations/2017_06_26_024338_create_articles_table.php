<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->string('keywords');

            $table->mediumText('description');
            $table->longText('body');
            
            $table->string('author');
            $table->integer('user_id');
            $table->integer('category_id');
            
            $table->string('image_url')->nullable()->index();
            $table->integer('status')->default(0)->index();
            $table->boolean('is_top')->default(0)->index();
            $table->string('source_url')->default(0)->index();
            $table->boolean('has_pic')->default(0)->index();

            $table->string('date')->nullable()->index();

            $table->integer('category_id')->unsigned()->change();
            $table->foreign('category_id')->references('id')->on('categories');

            $table->integer('author_id')->nullable();
            $table->string('user_name')->nullable();
            $table->timestamp('edited_at')->nullable();

            $table->integer('hits_mobile')->default(0);
            $table->integer('hits_phone')->default(0);
            $table->integer('hits_wechat')->default(0);
            $table->integer('hits_robot')->default(0);

            $table->string('image_top')->nullable();

            $table->integer('hits')->default(0);
            $table->integer('count_replies')->default(0);
            $table->integer('count_favorites')->default(0);
            $table->integer('count_shares')->default(0);

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
        Schema::dropIfExists('articles');
    }
}
