<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('store_id')->index();
            $table->unsignedInteger('category_id')->index();
            $table->unsignedInteger('parent_id')->index()->nullable();
            $table->Integer('video_id')->index()->comment('视频id');

            $table->String('name')->comment("名称:比如王者账号,衣服");

            //用于出售任何平台的账号商品
            // $table->json("platform_acounts")->index()->nullable()->comment("所有账号：[{platform:平台，account:账号，password:密码}]");

            $table->text('description')->comment("商品描述");
            $table->unsignedInteger('price')->comment('商品价格');
            $table->unsignedInteger('available_amount')->comment('商品上架中数量');
            $table->unsignedInteger('amount')->comment('商品总数量');

            //TODO: 租号时间不同规格时：parent_id 子产品关系, 子产品需要规格字段
            $table->String('dimension')->nullable()->index()->comment("规格，维度: 大号，1小时的，XL...");
            $table->String('dimension2')->default("安卓区")->nullable()->index()->comment("安卓区，ios区。。。");

            $table->Integer('status')->comment("1：上架，-1下架");
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
        Schema::dropIfExists('products');
    }
}
