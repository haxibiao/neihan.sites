<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWalletIdToGoldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gold', function (Blueprint $table) {
            if (!Schema::hasColumn('gold', 'wallet_id')) {
                $table->unsignedInteger('wallet_id')->nullable()->index();
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
        Schema::table('gold', function (Blueprint $table) {
            //
        });
    }
}
