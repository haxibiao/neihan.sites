<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceToPlatformAccountTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('platform_accounts', function (Blueprint $table) {
            //
            if (!Schema::hasColumn('platform_accounts', 'price')) {
                $table->unsignedInteger('price')->default(0)->comment('下单时的商品价格');
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
        Schema::table('platform_accounts', function (Blueprint $table) {
            //
        });
    }
}
