<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationPhasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //TODO: 这个阶段倍率表, 叫Phase表吧
        Schema::create('invitation_phases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('amount', 10, 2)->comment('目标金额');
            $table->decimal('rate', 8, 2)->comment('倍率');
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
        Schema::dropIfExists('invitation_phases');
    }
}
