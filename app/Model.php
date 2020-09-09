<?php

namespace App;

use Auth;
use Haxibiao\Base\Traits\ModelHelpers;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    use ModelHelpers;
    //time
    function getTimeAgoAttribute()
    {
        return diffForHumansCN($this->created_at);
    }

    function timeAgo()
    {
        return diffForHumansCN($this->created_at);
    }

    function createdAt()
    {
        return diffForHumansCN($this->created_at);
    }

    function updatedAt()
    {
        return diffForHumansCN($this->updated_at);
    }

    function editedAt()
    {
        return diffForHumansCN($this->edited_at);
    }


    //self
    function isSelf()
    {
        return Auth::check() && Auth::id() == $this->user_id;
    }

    function isOfUser($user)
    {
        return $user && $user->id == $this->user_id;
    }

    //json
    public function jsonData($key = null)
    {
        $jsonData = json_decode($this->json, true);
        if (empty($jsonData)) {
            $jsonData = [];
        }
        if (!empty($key)) {
            if (array_key_exists($key, $jsonData)) {
                return $jsonData[$key];
            }
            return null;
        }
        return $jsonData;
    }

    public function setJsonData($key, $value)
    {
        $data       = $this->jsonData();
        $data[$key] = $value;
        $this->json = json_encode($data, JSON_UNESCAPED_UNICODE);
        $this->save();
    }

    /*
     * 查询时排除相关列,减少传输消耗
     * @param  [type] $query [description]
     * @param  array  $value [description]
     * @return [type]        [description]
     */
    public function scopeExclude($query, $value = array())
    {
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

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
