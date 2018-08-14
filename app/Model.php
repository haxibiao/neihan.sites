<?php

namespace App;

use App\Traits\TimeAgo;
use App\Traits\UserRelated;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel {
	use TimeAgo;
	use UserRelated;
	/**
	 * 查询时排除相关列,减少传输消耗
	 * @param  [type] $query [description]
	 * @param  array  $value [description]
	 * @return [type]        [description]
	 */
	public function scopeExclude($query, $value = array()) {
		$columns = $this->getConnection()
			->getSchemaBuilder()
			->getColumnListing($this->getTable());
		return $query->select(array_diff($columns, (array) $value));
	}
	public function getTableColumns() {
		return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
	}
}
