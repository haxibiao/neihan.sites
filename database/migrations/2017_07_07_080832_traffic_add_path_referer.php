<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TrafficAddPathReferer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('traffic', function (Blueprint $table) {
            $table->string('path')->nullable();
            $table->string('referer')->nullable();
            $table->string('referer_domain')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('traffic', function (Blueprint $table) {
            //
        });
    }
}
