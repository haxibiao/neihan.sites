<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('product_id')->index();
            $table->unsignedInteger('order_id')->index();
            $table->unsignedInteger('price')->default(1)->comment('下单时的商品价格');
            $table->unsignedInteger('amount')->default(1)->comment('商品数量');
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
        Schema::dropIfExists('product_order');
    }
}
