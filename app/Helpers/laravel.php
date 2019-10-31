<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

function dropIndexIfExist($tableName, $indexName)
{
    Schema::table($tableName, function (Blueprint $table) use ($tableName, $indexName) {
        $sm            = Schema::getConnection()->getDoctrineSchemaManager();
        $doctrineTable = $sm->listTableDetails($tableName);

        if ($doctrineTable->hasIndex($indexName)) {
            $table->dropIndex($indexName);
        }
    });
}
