<?php
namespace App\GraphQL\Mutation\follow;

use App\Collection;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class followCollectionMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'followCollectionMutation',
        'description' => 'follow a Collection ',
    ];

    public function type()
    {
        return GraphQL::type('Collection');
    }

    public function args()
    {
        return [
            'collection_id' => ['name' => 'collection_id', 'type' => Type::int()],
            'undo'          => ['name' => 'undo', 'type' => Type::boolean()],
        ];
    }

    public function rules()
    {
        return [
            'collection_id' => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $user = getUser();

        $collection = Collection::findOrFail($args['collection_id']);
        if ((isset($args['undo']) && $args['undo']) || session('followed_collection_' . $args['collection_id'])) {
            session()->put('followed_collection_' . $args['collection_id'], 0);

            // delete follow Collection
            $follow = \App\Follow::where([
                'user_id'       => $user->id,
                'followed_id'   => $args['collection_id'],
                'followed_type' => 'collections',
            ])->first();
            if ($follow) {
                $follow->delete();
            }
        } else {
            session()->put('followed_collection_' . $args['collection_id'], 1);

            // save follow Collection
            $follow = \App\Follow::firstOrNew([
                'user_id'       => $user->id,
                'followed_id'   => $args['collection_id'],
                'followed_type' => 'collections',
            ]);
            $follow->save();

            // record action
            $action = \App\Action::create([
                'user_id'         => $user->id,
                'actionable_type' => 'follows',
                'actionable_id'   => $follow->id,
            ]);
        }
        $collection->count_follows = $collection->follows()->count();
        $collection->save();
        return $collection;
    }
}
