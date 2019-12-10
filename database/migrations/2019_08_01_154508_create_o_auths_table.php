<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOAuthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('o_auths', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->string('oauth_type');
            $table->string('oauth_id');
            $table->json('data')->nullable()->comment('json');
            $table->timestamps();

            $table->index(['user_id', 'oauth_type', 'oauth_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('o_auths');
    }
}
