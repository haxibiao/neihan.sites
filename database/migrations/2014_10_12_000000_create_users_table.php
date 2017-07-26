<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');

            $table->string('avatar')->nullable();
            $table->string('qq')->nullable();
            $table->string('introduction')->nullable();
            
            $table->boolean('is_editor')->default(0)->index();
            $table->boolean('is_admin')->default(0)->index();
            $table->integer('status')->default(0)->index();

            $table->boolean('is_seoer')->default(0)->index();
            $table->string('seo_meta')->nullable();
            $table->text('seo_push')->nullable();
            $table->text('seo_tj')->nullable();
            $table->text('seo_json')->nullable();
            
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
