<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable()->index();
            $table->string('path')->nullable()->index();
            $table->string('path_top')->nullable();
            $table->string('extension')->nullable()->index();
            $table->string('disk')->nullable();
            $table->string('source_url')->nullable();
            $table->integer('count')->default(0)->index();
            $table->integer('status')->default(0)->index();
            $table->integer('user_id')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->string('hash')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
