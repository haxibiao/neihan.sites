<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotInterestedOnUserBlocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_blocks', function (Blueprint $table) {
            if (!Schema::hasColumn('user_blocks', 'article_block_id')) {
                $table->bigInteger('article_block_id')->after('user_block_id')->nullable()->comment("不感兴趣的动态id");
            }
            if (!Schema::hasColumn('user_blocks', 'article_report_id')) {
                $table->Integer('article_report_id')->after('user_block_id')->nullable()->comment("被举报的动态id");
            }
            if (Schema::hasColumn('user_blocks', 'user_block_id')) {
                $table->bigInteger('user_block_id')->nullable()->change();
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
        Schema::table('user_blocks', function (Blueprint $table) {
            //
        });
    }
}
