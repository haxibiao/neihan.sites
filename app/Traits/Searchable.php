<?php


namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait Searchable
{

    protected $search_bindings = [];

    /**
     * 查询的核心类
     */
    public function scopeSearch(Builder $q, $search)
    {
        $query = clone $q;
        $query->select($this->getTable() . '.*');
        $this->makeJoins($query);

        $search = mb_strtolower(trim($search));
        preg_match_all('/(?:")((?:\\\\.|[^\\\\"])*)(?:")|(\S+)/', $search, $matches);
        $words = $matches[1];
        for ($i = 2; $i < count($matches); $i++) {
            $words = array_filter($words) + $matches[$i];
        }

        $selects = [];
        $this->search_bindings = [];
        $relevance_count = 0;

        foreach ($this->getColumns() as $column => $relevance)
        {
            $relevance_count += $relevance;

            $queries = $this->getSearchQueriesForColumn($column, $relevance, $words);

            foreach ($queries as $select)
            {
                if (!empty($select)) {
                    $selects[] = $select;
                }
            }
        }

        $this->addSelectsToQuery($query, $selects);

        // Default the threshold if no value was passed.
        $threshold = $relevance_count / count($this->getColumns());

        if (!empty($selects)) {
            $this->filterQueryWithRelevance($query, $selects, $threshold);
        }

        $this->makeGroupBy($query);

        $this->mergeQueries($query, $q);

        return $q;
    }

    /**
     * 获取数据库驱动
     * @return mixed
     */
    protected function getDatabaseDriver() {
        return $this->connection;
    }

    /**
     * 获取需要搜索的列
     * @return array
     */
    protected function getColumns()
    {
        if (array_key_exists('columns', $this->searchable)) {
            $driver = $this->getDatabaseDriver();
            $prefix = Config::get("database.connections.$driver.prefix");
            $columns = [];
            foreach($this->searchable['columns'] as $column => $priority){
                $columns[$prefix . $column] = $priority;
            }
            return $columns;
        // 如果没有配置就检索所有的列
        } else {
            return DB::connection()->getSchemaBuilder()->getColumnListing($this->table);
        }
    }

    /**
     * 获取需要连接查询的表
     * @return array|\ArrayAccess|mixed
     */
    protected function getJoins()
    {
        return Arr::get($this->searchable, 'joins', []);
    }

    /**
     * 拼接SQl Join schema
     * @param Builder $query
     */
    protected function makeJoins(Builder $query)
    {
        foreach ($this->getJoins() as $table => $keys) {
            $query->leftJoin($table, function ($join) use (&$keys) {
                $first = array_shift($keys);
                $join->on($first[0], '=', $first[1])->when(count($keys)>0,function ($q) use ($keys){
                    foreach($keys as $key ){
                        $q->where($key[0],'=',$key[1]);
                    }
                });
            });
        }
    }

    protected function makeGroupBy(Builder $query)
    {
        $columns = $this->getTable() . '.' .$this->primaryKey;
        $query->groupBy($columns);
    }

    protected function addSelectsToQuery(Builder $query, array $selects)
    {
        if (!empty($selects)) {
            $query->selectRaw('max(' . implode(' + ', $selects) . ') as ' . $this->getRelevanceField(), $this->search_bindings);
        }
    }

    protected function filterQueryWithRelevance(Builder $query, array $selects, $relevance_count)
    {
        $comparator =  $this->getRelevanceField();
        $relevance_count=number_format($relevance_count,2,'.','');

        $query->havingRaw("$comparator >= $relevance_count", []);
        $query->orderBy($this->getRelevanceField(), 'desc');

    }

    protected function getSearchQueriesForColumn($column, $relevance, array $words)
    {
        return [
            $this->getSearchQuery($column, $relevance, $words, 15),
            $this->getSearchQuery($column, $relevance, $words, 5, '', '%'),
            $this->getSearchQuery($column, $relevance, $words, 1, '%', '%')
        ];
    }

    protected function getSearchQuery($column, $relevance, array $words, $relevance_multiplier, $pre_word = '', $post_word = '')
    {
        $cases = [];

        foreach ($words as $word)
        {
            $cases[] = $this->getCaseCompare($column, 'LIKE', $relevance * $relevance_multiplier);
            $this->search_bindings[] = $pre_word . $word . $post_word;
        }

        return implode(' + ', $cases);
    }

    protected function getCaseCompare($column, $compare, $relevance) {
        $column = str_replace('.', '`.`', $column);
        $field = "LOWER(`" . $column . "`) " . $compare . " ?";
        return '(case when ' . $field . ' then ' . $relevance . ' else 0 end)';
    }

    protected function mergeQueries(Builder $clone, Builder $original) {
        $tableName = DB::connection($this->connection)->getTablePrefix() . $this->getTable();
        $original->from(DB::connection($this->connection)->raw("({$clone->toSql()}) as `{$tableName}`"));
        $mergedBindings = array_merge_recursive(
            $clone->getBindings(),
            $original->getBindings()
        );
        $original->withoutGlobalScopes()->setBindings($mergedBindings);
    }

    protected function getRelevanceField()
    {
        if ($this->relevanceField ?? false) {
            return $this->relevanceField;
        }
        return 'relevance';
    }
}