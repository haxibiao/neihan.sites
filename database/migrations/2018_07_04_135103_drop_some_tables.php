<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DropSomeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('article_video')) {
            Schema::drop('article_video');
        }

        if (Schema::hasTable('teams')) {
            Schema::drop('teams');
        }

        if (Schema::hasTable('team_user')) {
            Schema::drop('team_user');
        }

        if (Schema::hasTable('matches')) {
            Schema::drop('matches');
        }

        if (Schema::hasTable('compares')) {
            Schema::drop('compares');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
