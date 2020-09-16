<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWithdrawAtAndWithdrawLinesToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if(!Schema::hasColumn('users', 'withdraw_at')){
                $table->timestamp('withdraw_at')->nullable()->comments('最近提现提交的时间');
            }
            if(!Schema::hasColumn('users', 'withdraw_lines')){
                $table->unsignedInteger('withdraw_lines')->default(10)->comment('限量抢额度');
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
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
