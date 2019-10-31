<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropProfileFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'qq')) {
                $table->dropColumn('qq');
            }
            if (Schema::hasColumn('users', 'introduction')) {
                $table->dropColumn('introduction');
            }
            if (Schema::hasColumn('users', 'is_admin')) {
                $table->dropColumn('is_admin');
            }
            if (Schema::hasColumn('users', 'is_signed')) {
                $table->dropColumn('is_signed');
            }
            if (Schema::hasColumn('users', 'count_likes')) {
                $table->dropColumn('count_likes');
            }
            if (Schema::hasColumn('users', 'count_follows')) {
                $table->dropColumn('count_follows');
            }
            if (Schema::hasColumn('users', 'count_words')) {
                $table->dropColumn('count_words');
            }
            if (Schema::hasColumn('users', 'count_collections')) {
                $table->dropColumn('count_collections');
            }
            if (Schema::hasColumn('users', 'count_actions')) {
                $table->dropColumn('count_actions');
            }
            if (Schema::hasColumn('users', 'count_articles')) {
                $table->dropColumn('count_articles');
            }
            if (Schema::hasColumn('users', 'count_followings')) {
                $table->dropColumn('count_followings');
            }
            if (Schema::hasColumn('users', 'enable_tips')) {
                $table->dropColumn('enable_tips');
            }
            if (Schema::hasColumn('users', 'tip_words')) {
                $table->dropColumn('tip_words');
            }
            if (Schema::hasColumn('users', 'gender')) {
                $table->dropColumn('gender');
            }
            if (Schema::hasColumn('users', 'website')) {
                $table->dropColumn('website');
            }
            if (Schema::hasColumn('users', 'qrcode')) {
                $table->dropColumn('qrcode');
            }
            if (Schema::hasColumn('users', 'count_favorites')) {
                $table->dropColumn('count_favorites');
            }
            if (Schema::hasColumn('users', 'count_reports')) {
                $table->dropColumn('count_reports');
            }
            if (Schema::hasColumn('users', 'app')) {
                $table->dropColumn('app');
            }
            if (Schema::hasColumn('users', 'background')) {
                $table->dropColumn('background');
            }
            if (Schema::hasColumn('users', 'birthday')) {
                $table->dropColumn('birthday');
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
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
