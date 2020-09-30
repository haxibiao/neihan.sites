<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            if(!Schema::hasColumn('tasks','task_action')){
                $table->string('task_action')->nullable()->comment('任务对应的行为，如浏览，点赞，评论等');
            }
            if(!Schema::hasColumn('tasks','relation_class')){
                $table->string('relation_class')->nullable()->comment('任务对应的类，如合集，动态等');
            }
            if(!Schema::hasColumn('tasks','task_object')){
                $table->json('task_object')->nullable()->comment('任务指定的对象，如collections,posts数组等');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            //
        });
    }
}
