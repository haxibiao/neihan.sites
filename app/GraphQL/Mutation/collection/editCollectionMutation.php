<?php
namespace App\GraphQL\Mutation\collection;

use App\Collection;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class editCollectionMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'editCollectionMutation',
        'description' => 'create a Collection ',
    ];

    public function type()
    {
        return GraphQL::type('Collection');
    }

    public function args()
    {
        return [
            'id'   => ['name' => 'id', 'type' => Type::int()],
            'name' => ['name' => 'name', 'type' => Type::string()],
        ];
    }

    public function rules()
    {
        return [
            'id'   => ['required'],
            'name' => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $collection       = Collection::findOrFail($args['id']);
        $collection->name = $args['name'];
        $collection->update();

        return $collection;
    }
}
