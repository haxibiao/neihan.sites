<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditArticlesAddSubmit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {

            if (!Schema::hasColumn('articles', 'submit')) {
                $table->tinyInteger('submit')->default(1)->index()->comment('审核状态: -1 已拒绝, 0 审核中, 1 已收录');
                $table->string('remark')->nullable()->comment('备注');
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
        Schema::table('articles', function (Blueprint $table) {
            //
        });
    }
}
