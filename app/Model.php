<?php

namespace App;

use App\Traits\TimeAgo;
use App\Traits\UserRelated;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel {
    use TimeAgo;
    use UserRelated;
    /*
     * 查询时排除相关列,减少传输消耗
     * @param  [type] $query [description]
     * @param  array  $value [description]
     * @return [type]        [description]
     */
    public function scopeExclude($query, $value = array()) {
        //获取该表所有列
        $columns = $this->getTableColumns();
        //需要获取列名
        $real_columns = array_diff($columns, (array) $value);
        
        $tableName = $this->getTable();
        $format_colomns = array_map(function ($name) use ($tableName) {
            return $tableName . '.' . $name;
        }, $real_columns); 
        return $query->select($format_colomns);
    }

    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}



