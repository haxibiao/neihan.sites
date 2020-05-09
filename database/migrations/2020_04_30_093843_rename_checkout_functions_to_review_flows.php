<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameCheckoutFunctionsToReviewFlows extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('review_flows', function (Blueprint $table) {
            if (Schema::hasColumn('review_flows', 'checkout_functions')) {
                $table->renameColumn('checkout_functions', 'check_functions');
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
        Schema::table('review_flows', function (Blueprint $table) {
            //
        });
    }
}
