<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('path')->nullable();
            $table->integer('user_id')->default(0)->index();
            $table->integer('duration')->default(0); //in seconds
            $table->integer('status')->default(0)->index();
            $table->string('hash')->nullable()->index();
            $table->text('json')->nullable();
            $table->string('adstime')->nullable();

            $table->timestamps();

            //TODO：这里日后可以用laravel建议的软删除
            $table->softDeletes();

            $table->string('qcvod_fileid')->nullable()->index();
            $table->string('cover')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
