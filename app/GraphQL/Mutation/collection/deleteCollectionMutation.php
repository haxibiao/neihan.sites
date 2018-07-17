<?php
namespace App\GraphQL\Mutation\collection;

use App\Collection;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class deleteCollectionMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'deleteCollectionMutation',
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
        $collection       = Collection::findOrFail($args['id']);
        if($collection->articles()->count()){
            throw new \Exception("文集下面还有文章,无法删除");
        }
        $collection->delete();

        return $collection;
    }
}
