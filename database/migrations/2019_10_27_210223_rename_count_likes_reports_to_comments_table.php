<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameCountLikesReportsToCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {

            if (Schema::hasColumn('comments', 'likes')) {
                if (!Schema::hasColumn('comments', 'count_likes')) {
                    $table->renameColumn('likes', 'count_likes');
                }
            }
            if (Schema::hasColumn('comments', 'reports')) {
                $table->renameColumn('reports', 'count_reports');
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
        Schema::table('comments', function (Blueprint $table) {
            //
        });
    }
}
