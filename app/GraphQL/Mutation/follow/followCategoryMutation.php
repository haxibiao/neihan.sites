<?php
namespace App\GraphQL\Mutation\follow;

use App\Category;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class followCategoryMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'followCategoryMutation',
        'description' => 'follow a Category ',
    ];

    public function type()
    {
        return GraphQL::type('Category');
    }

    public function args()
    {
        return [
            'category_id' => ['name' => 'category_id', 'type' => Type::int()],
            'undo'          => ['name' => 'undo', 'type' => Type::boolean()],
        ];
    }

    public function rules()
    {
        return [
            'category_id' => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $user = getUser();

        $category = Category::findOrFail($args['category_id']);
        if ((isset($args['undo']) && $args['undo']) || session('followed_category_' . $args['category_id'])) {
            session()->put('followed_category_' . $args['category_id'], 0);

            // delete follow Category
            $follow = \App\Follow::where([
                'user_id'       => $user->id,
                'followed_id'   => $args['category_id'],
                'followed_type' => 'categories',
            ])->first();
            if ($follow) {
                $follow->delete();
            }
        } else {
            session()->put('followed_category_' . $args['category_id'], 1);

            // save follow Category
            $follow = \App\Follow::firstOrNew([
                'user_id'       => $user->id,
                'followed_id'   => $args['category_id'],
                'followed_type' => 'categories',
            ]);
            $follow->save();

            // record action
            $action = \App\Action::create([
                'user_id'         => $user->id,
                'actionable_type' => 'follows',
                'actionable_id'   => $follow->id,
            ]);
        }
        $category->count_follows = $category->follows()->count();
        $category->save();
        return $category;
    }
}
