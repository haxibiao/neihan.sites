<?php
namespace App\GraphQL\Mutation\queryLog;

use App\Querylog;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type; 

class DeleteQueryLogMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'DeleteQueryLogMutation',
        'description' => '删除查询日志', 
    ];

    public function type()
    {
        return Type::listOf( GraphQL::type('QueryLog') ); 
    }

    public function args()
    {
        return [ 
            'id'   => ['name' => 'id', 'type' => Type::int()],
        ];
    } 

    public function resolve($root, $args)
    {
        if(!checkUser()){
            return null;
        }
        //下面代码存在性能问题,后面有时间我会过来修。先上线用
        if( isset($args['id']) && !empty($args['id']) ){
            $querylog = Querylog::where('id', $args['id'])->get();
            Querylog::where('id', $args['id'])->delete();
            return $querylog;
        }
        
        $user = getUser();
        $queryLogs = $user->querylogs()->get();
        if( empty($queryLogs) ){
            return null;
        }
        $user->querylogs()->delete();
        return $queryLogs; 

    }
}
