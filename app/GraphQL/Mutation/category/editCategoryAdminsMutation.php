<?php
namespace App\GraphQL\Mutation\category;

use App\Category;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class editCategoryAdminsMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'editCategoryAdminsMutation',
        'description' => 'edit a Category admins ',
    ];

    public function type()
    {
        return GraphQL::type('Category');
    }

    public function args()
    {
        return [
            'id'         => ['name' => 'id', 'type' => Type::int()],
            'admin_uids' => ['name' => 'admin_uids', 'type' => Type::string()],
        ];
    }

    public function rules()
    {
        return [
            'id' => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $user = getUser();

        $category = Category::findOrFail($args['id']);
        $data = [
            $user->id => ['is_admin' => 1],
        ];
        if (!empty($args['admin_uids'])) {
            $admin_uids = explode(",", $args['admin_uids']);
            foreach ($admin_uids as $uid) {
                if (intval($uid)) {
                    $data[$uid] = ['is_admin' => 1];
                }
            }
            $category->admins()->sync($data);
        }

        return $category;
    }
}
