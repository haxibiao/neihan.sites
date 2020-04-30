<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->index();
            // $table->String("account")->nullable()->comment("下单时的账号");
            // $table->String("password")->nullable()->comment("下单时的密码");
            $table->String('number')->index()->comment("订单号");
            $table->Integer('status')->comment("订单状态：0:未支付, 1:已支付|可用中, 2:已收货, 3：已过期(账号时间到)");
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
        Schema::dropIfExists('orders');
    }
}
