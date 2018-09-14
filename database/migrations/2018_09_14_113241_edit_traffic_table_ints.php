<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTrafficTableInts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('traffic', function (Blueprint $table) {
             $table->integer('year')->change();
             $table->integer('month')->change();
             $table->integer('day')->change();
+
             $table->integer('dayOfWeek')->change();
             $table->integer('dayOfYear')->change();
             $table->integer('daysInMonth')->change();
             $table->integer('weekOfMonth')->change();
             $table->integer('weekOfYear')->change();
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
