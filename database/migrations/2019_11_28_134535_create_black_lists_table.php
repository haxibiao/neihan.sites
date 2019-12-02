<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlackListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('black_lists')) {
            Schema::create('black_lists', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('user_id')->index();
                $table->string('comment')->nullable()->comment('意见');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('black_lists');
    }
}
