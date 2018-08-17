<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index();
            $table->string('type')->index();
            $table->string('log')->nullable();
            $table->decimal('amount');
            $table->string('status')->index();
            $table->decimal('balance')->default(0);

            $table->integer('from_user_id')->nullable();
            $table->integer('to_user_id')->nullable();
            $table->integer('relate_id')->nullable();
            
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
        Schema::dropIfExists('transactions');
    }
}
