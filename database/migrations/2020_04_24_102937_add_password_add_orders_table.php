<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPasswordAddOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'account')) {
                $table->String("account")->after("user_id")->nullable()->comment("下单时的账号");
            }
            if (!Schema::hasColumn('orders', 'password')) {
                $table->String("password")->after("account")->nullable()->comment("下单时的密码");
            }
        });

        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {

        });
        //
    }
}
