<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('seos')) {
            return;
        }
        Schema::create('seos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('group')->comment('SEO功能组');
            $table->string('name')->comment('SEO名称');
            $table->text('value')->nullable()->comment('SEO相关值');
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
        Schema::dropIfExists('seos');
    }
}
