<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlatformAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('product_id')->index();
            $table->unsignedInteger('order_id')->nullable()->index()->comment("租到某订单内的关系");

            //开始：可用中，被租了:使用中，订单过期：维护中.., 运营操作后：可用中/不可用 ....
            $table->Integer('order_status')->default(0)->comment("账号状态：0:可用中, 1:使用中, 2:维护中 -1：不可用");
            $table->unsignedInteger('price')->default(0)->comment('下单时的商品价格');

            $table->String('dimension')->nullable()->index()->comment("规格，维度: 大号,至尊宝，紫霞仙子。。。");
            $table->String('dimension2')->nullable()->index()->comment("规格，维度:1小时，2小时。。。");

            $table->String('platform')->index()->comment("平台：优酷，王者，LOL, 某论坛");
            $table->String('account')->comment("账号");
            $table->String('password')->comment("密码");
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
        Schema::dropIfExists('platform_accounts');
    }
}
