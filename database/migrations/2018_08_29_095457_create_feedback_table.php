<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content')
                ->nullable()
                ->comment('反馈的内容');

            $table->unsignedInteger('user_id')
                ->nullable()
                ->comment('反馈的用户');

            $table->string('contact')
                ->nullable()
                ->comment('联系方式');

            $table->string('contact_type')
                ->nullable()
                ->comment('联系方式: phone:手机 email:邮箱 ');

            $table->unsignedInteger('status')->default(0)->comment('0-待处理 1-已驳回 2-已处理');

            $table->timestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedback');
    }
}
