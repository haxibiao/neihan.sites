<?php

namespace App\GraphQL\Query;

use App\Visit;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\Type;

class VisitsQuery extends Query
{
    protected $attributes = [
        'name'        => 'Visit',
        'description' => 'return a Visit',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Visit'));
    }

    public function args()
    {
        return [
            'offset' => ['name' => 'offset', 'type' => Type::int()],
            'limit'  => ['name' => 'limit', 'type' => Type::int()],
            'filter' => ['name' => 'filter', 'type' => GraphQL::type('VisitFilter')],
        ];
    }

    public function rules()
    {
        return [
            'filter' => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $user  = getUser();
        
        $query = Visit::where('user_id', $user->id)->where('visited_type', 'articles');
        $query->when($args['filter'] == 'TODAY', function ($q) {
            return $q->whereDay('updated_at', date('d'));
        });
        $query->when($args['filter'] == 'EARLY', function ($q) {
            return $q->whereDate('updated_at', '<', date('Y-m-d'));
        });

        //获取请求参数
        if (isset($args['offset'])) {
            $query = $query->skip($args['offset']);
        }
        $limit = 15; //默认15条历史记录
        if (isset($args['limit'])) {
            $limit = $args['limit'];
        }
        $query = $query->take($limit);
        return $query->get();
    }
}
