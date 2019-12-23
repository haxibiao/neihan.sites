<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFunctionSwitchsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('function_switchs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('关闭的功能');
            $table->unsignedInteger('state')->default(1)->comment('状态，1 开启 0 关闭');
            $table->string('close_details')->comment('关闭理由');
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
        Schema::dropIfExists('function_switchs');
    }
}
