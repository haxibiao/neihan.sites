<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaggableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('taggables')) {
            Schema::drop("taggables");
        }

        Schema::create('taggables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tag_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->morphs('taggable');
            $table->string('tag_name');
            $table->timestamps();

            $table->unique(['tag_id', 'taggable_id', 'taggable_type']);
            $table->index('user_id');
            $table->index('tag_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taggables', function (Blueprint $table) {
            //
        });
    }
}
