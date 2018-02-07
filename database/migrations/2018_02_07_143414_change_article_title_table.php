<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeArticleTitleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->integer('collection_id')->nullable()->index();
            $table->string('keywords')->nullable()->change();
            $table->longText('body')->nullable()->change();
            $table->string('author')->nullable()->change();
            $table->integer('author_id')->nullable()->change();
            $table->dropForeign('articles_category_id_foreign');
            $table->dropUnique('articles_title_unique')->change();
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
