<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TrafficAddDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('traffic', function (Blueprint $table) {
            $table->string('date')->nullable()->index();
            $table->string('year')->nullable()->index();
            $table->string('month')->nullable()->index();
            $table->string('day')->nullable()->index();
            $table->string('dayOfWeek')->nullable()->index();
            $table->string('dayOfYear')->nullable()->index();
            $table->string('daysInMonth')->nullable()->index();
            $table->string('weekOfMonth')->nullable()->index();
            $table->string('weekOfYear')->nullable()->index();
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
