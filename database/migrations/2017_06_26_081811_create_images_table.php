<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->Integer('store_id')->index()->comment('店铺id');
            $table->Integer('product_id')->index()->comment('商品id');

            $table->string('title')->nullable()->index()->comment('图片最后一次配文时的标题，方便搜索图片用');
            $table->string('source_url')->nullable()->comment('外链地址,disk为null时需要采集回来');
            $table->string('hash')->nullable()->unique();
            $table->string('path')->nullable()->index();

            $table->string('path_top')->nullable(); // 影响旧web，暂时不动

            $table->string('extension')->nullable()->index();
            $table->string('disk')->nullable();

            $table->integer('count')->default(0)->index()->comment('图片被引用次数，查询热门图片可用');
            $table->integer('status')->default(0)->index()->comment('-1：删除，0:隐藏，1:公开');

            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
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
        Schema::dropIfExists('images');
    }
}
