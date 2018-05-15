<?php

namespace App\GraphQL\Type;

use Folklore\GraphQL\Support\Facades\GraphQL;
use Folklore\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class CollectionType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'Collection',
        'description' => 'A Collection',
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */
    // protected $inputObject = true;

    public function fields()
    {
        return [
            'id'             => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'The id of the Collection',
            ],
            'name'           => [
                'type'        => Type::string(),
                'description' => 'nick name',
            ],
            'logo'           => [
                'type'        => Type::string(),
                'description' => 'logo url of Collection',
                'resolve'     => function ($root, $args) {
                    return "https://dongmeiwei.com/images/collection.png";
                },
            ],
            'count_follows'  => [
                'type'        => Type::int(),
                'description' => 'follows count of Collection',
            ],
            'count_articles' => [
                'type'        => Type::int(),
                'description' => 'articles count of Collection',
                'resolve'     => function ($root, $args) {
                    return $root->count;
                },
            ],
            'user'           => [
                'type'        => GraphQL::type('User'),
                'description' => '文集的所有者',
                'resolve'     => function ($root, $args) {
                    return $root->user;
                },
            ],

            'followed'        => [
                'type'        => Type::boolean(),
                'description' => '当前用户是否关注本collection',
                'resolve'     => function ($root, $args) {
                    if ($user = session('user')) {
                        return $user->followingCollections->contains((function ($item, $key) use ($root) {
                            return $item->followed->id == $root->id;
                        }));
                    }
                    return false;
                },
            ],
        ];
    }
}
