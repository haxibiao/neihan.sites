<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrderStatusToPlatformAccountTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('platform_account_tables', function (Blueprint $table) {
            //开始：可用中，被租了:使用中，订单过期：维护中.., 运营操作后：可用中/不可用 ....
            if (Schema::hasColumn('orders', 'order_status')) {
                $table->dropColumn('order_status');
                $table->Integer('order_status')->default(0)->comment("账号状态：0:可用中, 1:使用中, 2:维护中 -1：不可用");
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
        Schema::table('platform_account_tables', function (Blueprint $table) {
            //
        });
    }
}
