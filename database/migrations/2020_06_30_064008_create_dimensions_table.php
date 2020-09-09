<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDimensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('dimensions')) {
            return;
        }
        Schema::create('dimensions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index()->comment('统计的用户');
            $table->string('name')->index()->comment('重要维度的名称');
            $table->unsignedInteger('value')->default(0)->comment('值');
            $table->unsignedInteger('count')->default(1)->comment('次数');
            $table->timestamps();
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dimensions');
    }
}
