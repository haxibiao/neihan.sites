<?php
namespace App\GraphQL\Mutation\category;

use App\Category;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class deleteCategoryMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'deleteCategoryMutation',
        'description' => 'create a Category ',
    ];

    public function type()
    {
        return GraphQL::type('Category');
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
        $category       = Category::findOrFail($args['id']);
        if($category->articles()->count()){
            throw new Exception("专题下面还有文章,无法删除");
        }
        $category->delete();

        return $category;
    }
}
