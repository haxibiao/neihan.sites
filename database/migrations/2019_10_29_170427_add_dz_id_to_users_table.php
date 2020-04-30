<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDzIdToUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'dz_id')) {
                $table->unsignedInteger('dz_id')->nullable()->unique()->comment('答题赚钱ID');
            }
            if (!Schema::hasColumn('users', 'account')) {
                //nullable 兼容旧系统account字段没有的情况
                $table->string('account')->nullable()->unique()->comment('账号');
            }

            // $table->dropIndexIfExists('users_name_unique');
            dropIndexIfExist('users', 'users_name_unique');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
