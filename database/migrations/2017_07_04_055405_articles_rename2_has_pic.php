<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ArticlesRename2HasPic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {            
            if (Schema::hasColumn('articles', 'is_has_pic')) {
                $table->renameColumn('is_has_pic', 'has_pic');
            } else {
                if (!Schema::hasColumn('articles', 'has_pic')) {
                    $table->boolean('has_pic')->default(0)->index();
                }
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
        Schema::table('articles', function (Blueprint $table) {
            //
        });
    }
}
