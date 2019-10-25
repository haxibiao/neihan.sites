<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddUuidOnUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::table('users', function (Blueprint $table) {

            if (!Schema::hasColumn('users', 'email')) {
                $table->string('email')->unique()->nullable();
            } else {
                DB::statement('ALTER TABLE `users` MODIFY `email` varchar(191) NULL;');
            }

           if (!Schema::hasColumn('users', 'uuid')) {
               $table->string('uuid')->nullable()->comment('用户设备唯一标识');
           }
           if (!Schema::hasColumn('users', 'phone')) {
               $table->string('phone')->nullable()->comment('用户手机号');
           }

            // 为方便静默登录 设置一下列允许空
            DB::statement('ALTER TABLE `users` MODIFY `password` varchar(191) NULL;');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
