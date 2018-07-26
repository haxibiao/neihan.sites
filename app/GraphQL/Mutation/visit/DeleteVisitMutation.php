<?php
namespace App\GraphQL\Mutation\visit;

use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use App\Visit;
use GraphQL\Type\Definition\Type;

class DeleteVisitMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'DeleteVisitMutation',
        'description' => '删除用户浏览记录',
    ];

    public function type()
    {
        return GraphQL::type('Visit'); 
    }

    public function args()
    {
        return [
            'id'   => ['name' => 'id', 'type' => Type::int()],
        ];
    }

    public function rules()
    {
        return [
            'id'   => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
    	if(!checkUser()){
    		throw new \Exception("客户端还没登录...");
    	}
    	$visit       = Visit::findOrFail($args['id']);
        $visit->delete();
        return $visit;
    }
}
