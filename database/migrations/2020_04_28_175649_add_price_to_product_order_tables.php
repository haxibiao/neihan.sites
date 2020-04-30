<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceToProductOrderTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_order', function (Blueprint $table) {
            //
            if (!Schema::hasColumn('product_order', 'price')) {
                $table->unsignedInteger('price')->default(1)->comment('下单时的商品价格');
            }

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_order', function (Blueprint $table) {
            //
        });
    }
}
