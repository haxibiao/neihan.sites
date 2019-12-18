<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRetentionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_retentions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->index()->comment('用户ID');;
            $table->timestamp('next_day_retention_at')->nullable()->index()->comment('次日留存');
            $table->timestamp('third_day_retention_at')->nullable()->index()->comment('三日留存');
            $table->timestamp('fifth_day_retention_at')->nullable()->index()->comment('五日留存');
            $table->timestamp('sixth_day_retention_at')->nullable()->index()->comment('七日留存');
            $table->timestamp('month_retention_at')->nullable()->index()->comment('月留存');
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
        Schema::dropIfExists('user_retentions');
    }
}
