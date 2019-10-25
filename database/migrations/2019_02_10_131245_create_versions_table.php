<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('versions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('版本名称');
            $table->unsignedInteger('build')->default(0);
            $table->string('description')->nullable()->comment('描述');
            $table->unsignedInteger('size')->default(0)->comment('文件大小');
            $table->string('url')->nullable();
            $table->string('os', 30)->nullable();
            $table->tinyInteger('type')->default(0)->comment('类型: 0:内测包 1:正式包');
            $table->boolean('is_force')->default(0)->comment('是否强制更新: 0否 1:强更');
            $table->boolean('status')->default(0)->comment('状态 1:上架 0:下架 -1:删除');
            $table->string('package', 30)->nullable()->comment('包名');
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
        Schema::dropIfExists('versions');
    }
}
