<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCategoriesComunTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->integer('request_status')->default(0); //0: submit request dont need approve, 1: need approve
            $table->boolean('is_official')->default(0);
            $table->boolean('is_for_app')->default(0);
            $table->string('logo_app')->nullable();
            //新投稿标题
            $table->string('new_request_title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            //
        });
    }
}
