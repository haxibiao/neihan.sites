<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditCategoryUserTableAddCountApproved extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_user', function (Blueprint $table) {
            $table->renameColumn('approved', 'count_approved');
            // $table->integer('count_approved')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_user', function (Blueprint $table) {
            //
        });
    }
}
