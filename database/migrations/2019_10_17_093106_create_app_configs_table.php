<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppConfigsTable extends Migration
{

    public function up()
    {
        if (Schema::hasTable('app_configs')) {
            Schema::drop('app_configs');
        }
        Schema::create('app_configs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('group')->comment('功能组：ios huawei android');
            $table->string('name')->comment('功能名称, 例子：ad,wallet');
            $table->unsignedInteger('state')->default(1)->comment('状态，1 开启 0 关闭');
            $table->json('data')->nullable()->comment('app配置信息');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('app_configs');
    }
}
