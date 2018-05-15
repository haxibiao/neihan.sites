<?php
namespace App\GraphQL\Mutation\collection;

use App\Collection;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class createCollectionMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'createCollectionMutation',
        'description' => 'create a Collection ',
    ];

    public function type()
    {
        return GraphQL::type('Collection');
    }

    public function args()
    {
        return [
            'name'        => ['name' => 'name', 'type' => Type::string()],
        ];
    }

    public function rules()
    {
        return [
            'name' => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $user = getUser();

        $collection=Collection::firstOrNew([
            'name'=>$args['name']
        ]);
        $collection->user_id=$user->id;
        $collection->save();

        return $collection;
    }
}
