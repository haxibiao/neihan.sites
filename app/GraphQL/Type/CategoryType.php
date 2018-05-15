<?php

namespace App\GraphQL\Type;

use Folklore\GraphQL\Support\Facades\GraphQL;
use Folklore\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class CategoryType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'Category',
        'description' => 'A category',
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */
    // protected $inputObject = true;

    public function fields()
    {
        return [
            'id'              => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'The id of the category',
            ],
            'user_id'         => [
                'type'        => Type::int(),
                'description' => 'category creator user id',
            ],
            'name'            => [
                'type'        => Type::string(),
                'description' => 'category name',
            ],
            'name_en'         => [
                'type'        => Type::string(),
                'description' => 'category name_en',
            ],
            'description'     => [
                'type'        => Type::string(),
                'description' => 'description of category',
            ],
            'logo'            => [
                'type'        => Type::string(),
                'description' => 'logo url of category',
            ],
            'allow_submit'    => [
                'type'        => Type::boolean(),
                'description' => 'allow submit',
                'resolve'     => function ($root, $args) {
                    return $root->status;
                },
            ],
            'need_approve'    => [
                'type'        => Type::boolean(),
                'description' => 'need approve',
                'resolve'     => function ($root, $args) {
                    return $root->request_status;
                },
            ],
            'logo_app'        => [
                'type'        => Type::string(),
                'description' => 'logo_app url of category',
            ],
            'count_follows'   => [
                'type'        => Type::int(),
                'description' => 'follows count of category',
            ],
            'count_articles'  => [
                'type'        => Type::int(),
                'description' => 'follows count of category',
                'resolve'     => function ($root, $args) {
                    return $root->count;
                },
            ],
            'count_authors'   => [
                'type'        => Type::int(),
                'description' => 'authors count of category',
            ],
            'new_requests'    => [
                'type'        => Type::int(),
                'description' => 'new requests of category',
            ],

            'followed'        => [
                'type'        => Type::boolean(),
                'description' => '当前用户是否关注本专题',
                'resolve'     => function ($root, $args) {
                    if ($user = session('user')) {
                        return $user->followingCategories->contains((function ($item, $key) use ($root) {
                            return $item->followed->id == $root->id;
                        }));
                    }
                    return false;
                },
            ],

            //computed
            'articles'        => [
                'type'        => Type::listOf(GraphQL::type('Article')),
                'args'        => [
                    'all' => ['name' => 'all', 'type' => Type::boolean()],
                ],
                'description' => 'category related articles, including newly requested ...',
                'resolve'     => function ($root, $args) {
                    if (isset($args['all']) && $args['all']) {
                        return $root->articles;
                    }
                    return $root->newRequestArticles;
                },
            ],

            //relation ...
            'user'            => [
                'type'        => GraphQL::type('User'),
                'description' => 'creator of category',
            ],
            'latestArticle'   => [
                'type'        => GraphQL::type('Article'),
                'description' => 'latest article of category',
                'resolve'     => function ($root, $args) {
                    return $root->articles()->first();
                },
            ],
            'latest_follower' => [
                'type'        => GraphQL::type('User'),
                'description' => 'latest follower of category',
            ],
            'authors'         => [
                'type'        => Type::listOf(GraphQL::type('User')),
                'description' => 'authors of category',
                'args'        => [
                    'offset' => ['name' => 'offset', 'type' => Type::int()],
                    'limit'  => ['name' => 'limit', 'type' => Type::int()],
                ],
                'resolve'     => function ($root, $args) {
                    $qb = $root->authors();

                    if (isset($args['offset'])) {
                        $qb = $qb->skip($args['offset']);
                    }
                    $limit = 10;
                    if (isset($args['limit'])) {
                        $limit = $args['limit'];
                    }
                    $qb = $qb->take($limit);
                    return $qb->get();
                },
            ],
            'admins'          => [
                'type'        => Type::listOf(GraphQL::type('User')),
                'description' => 'admins of category',
                'args'        => [
                    'offset' => ['name' => 'offset', 'type' => Type::int()],
                    'limit'  => ['name' => 'limit', 'type' => Type::int()],
                ],
            ],
            'follows'         => [
                'type'        => Type::listOf(GraphQL::type('Follow')),
                'description' => 'follows of category',
                'args'        => [
                    'offset' => ['name' => 'offset', 'type' => Type::int()],
                    'limit'  => ['name' => 'limit', 'type' => Type::int()],
                ],
            ],
        ];
    }

    public function resolveFollowsField($root, $args)
    {
        $qb = $root->follows();

        if (isset($args['offset'])) {
            $qb = $qb->skip($args['offset']);
        }
        $limit = 10;
        if (isset($args['limit'])) {
            $limit = $args['limit'];
        }
        $qb = $qb->take($limit);
        return $qb->get();
    }

    public function resolveAdminsField($root, $args)
    {
        $qb = $root->admins();

        if (isset($args['offset'])) {
            $qb = $qb->skip($args['offset']);
        }
        $limit = 10;
        if (isset($args['limit'])) {
            $limit = $args['limit'];
        }
        $qb = $qb->take($limit);
        return $qb->get();
    }

    public function resolveUserField($root, $args)
    {
        return $root->user;
    }

    public function resolveLogoField($root, $args)
    {
        return $root->logo();
    }

    public function resolveLogoAppField($root, $args)
    {
        return $root->logo_app();
    }

    public function resolveLatestFollowerField($root, $args)
    {
        $latest_followers = $root->topFollowers();
        $user             = null;
        if (count($latest_followers)) {
            $user = $latest_followers[0];
        }

        return $user;
    }
}
