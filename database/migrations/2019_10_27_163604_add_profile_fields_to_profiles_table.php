<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileFieldsToProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('profiles', 'birthday')) {
                $table->timestamp('birthday')->nullable()->comment('生日');
            }
            if (!Schema::hasColumn('profiles', 'age')) {
                $table->unsignedInteger('age')->default(0)->comments('年龄,如果设置了生日，自动更新年龄');
            }
            if (!Schema::hasColumn('profiles', 'background')) {
                $table->string('background')->nullable()->comment('用户背景图');
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
        Schema::table('profiles', function (Blueprint $table) {
            //
        });
    }
}
