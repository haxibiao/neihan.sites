<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersCountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('count_comments')->default(0);
            $table->integer('count_favorites')->default(0);
            $table->integer('count_shares')->default(0);
            $table->integer('count_tips')->default(0);
            $table->integer('count_articles')->default(0);
            $table->integer('count_words')->default(0);
 
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
            $table->dropColumn('count_comments');
            $table->dropColumn('count_favorites');
            $table->dropColumn('count_shares');
            $table->dropColumn('count_tips');
            $table->dropColumn('count_articles');
        });
    }
}
