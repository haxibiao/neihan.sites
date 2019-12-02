<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropDeviceIdAndExpiredAtToBlackListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('black_lists', function (Blueprint $table) {
            if (Schema::hasColumn('black_lists', 'expired_at')) {
                $table->dropColumn(['expired_at', 'device_id']);
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
        Schema::table('black_list', function (Blueprint $table) {
            //
        });
    }
}
