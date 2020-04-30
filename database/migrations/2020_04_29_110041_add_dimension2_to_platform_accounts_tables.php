<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDimension2ToPlatformAccountsTables extends Migration
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
            if (!Schema::hasColumn('platform_accounts', 'dimension2')) {
                $table->String('dimension2')->after("dimension")->nullable()->index()->comment("规格，维度: 1小时，2小时。。。");
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
