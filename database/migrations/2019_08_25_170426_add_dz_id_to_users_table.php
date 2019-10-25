<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDzIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'dz_id')) {
                $table->unsignedInteger('dz_id')->nullable()->unique()->comment('答题赚钱ID');
            }
//           if (!Schema::hasColumn('users', 'account')) {
//               $table->string('account')->unique()->comment('账号');
//           }

            //$table->dropIndex('users_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
