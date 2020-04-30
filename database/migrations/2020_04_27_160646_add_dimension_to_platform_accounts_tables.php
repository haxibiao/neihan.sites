<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDimensionToPlatformAccountsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('platform_accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('platform_accounts', 'dimension')) {
                $table->String('dimension')->index()->comment("规格，维度: 大号,至尊宝，紫霞仙子。。。");
                //
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
