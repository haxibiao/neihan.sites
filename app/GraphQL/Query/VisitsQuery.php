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
            'limit'  => ['name' => 'limit', 'type'  => Type::int()],
            'filter' => ['name' => 'filter', 'type' => GraphQL::type('VisitFilter')],
            'type'   => ['name' => 'type'  ,  'type'=> GraphQL::type('VisitTypeEnum')] 
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

        $query = Visit::with('visited')->orderBy('updated_at','desc')->where('user_id', $user->id);
        //浏览资源类型
        $query->when( isset($args['type'] ) , function ($q) use ($args){
            return $q->where('visited_type', $args['type']);
        });
         
        //过滤条件
        if( isset($args['filter']) ){
            $query->when($args['filter'] == 'TODAY', function ($q) {
                return $q->whereDay('updated_at', date('d'));
            });
            $query->when($args['filter'] == 'EARLY', function ($q) {
                return $q->whereDate('updated_at', '<', date('Y-m-d'));
            }); 
        }

        //分页参数
        $offset = isset($args['offset'])? $args['offset']: 0;
        $limit  = isset($args['limit'])? $args['limit']  : 15;//默认15条历史记录

        $query = $query->skip($offset)
            ->take($limit); 

        return $query->get();
    }
}
