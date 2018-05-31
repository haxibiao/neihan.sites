<?php

namespace App\GraphQL\Type;

use App\Category;
use Folklore\GraphQL\Support\Facades\GraphQL;
use Folklore\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class ArticleType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'Article',
        'description' => 'A Article',
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
                'description' => 'The id of the article',
            ],
            'comments'        => [
                'type'        => Type::listOf(GraphQL::type('Comment')),
                'description' => 'comments of article',
            ],
            'title'           => [
                'type'        => Type::string(),
                'description' => 'The title of article',
            ],
            'image_url'       => [
                'type'        => Type::string(),
                'description' => 'The image_url of article',
                'resolve'     => function ($root, $args) {
                    // var_dump($root); die;
                    return $root ? $root->primaryImage() : null;
                },
            ],
            'top_image'       => [
                'type'        => Type::string(),
                'description' => 'The top image_url of article',
                'resolve'     => function ($root, $args) {
                    return $root->topImage();
                },
            ],
            'description'     => [
                'type'        => Type::string(),
                'description' => 'The description of article',
                'resolve'     => function ($root, $args) {
                    $description = !empty($root->description) ? $root->description
                    : str_limit(trim(strip_tags(preg_replace('/\s+/', ' ', $root->body))), $limit = 100, $end = '...');
                    return str_limit($description);
                },
            ],
            'body'            => [
                'type'        => Type::string(),
                'description' => 'The body of article',
            ],
            'status'          => [
                'type'        => Type::int(),
                'description' => '-1: 回收站, 0:草稿(未公开)，1:发布',
            ],

            //computed

            'reports'      => [
                'type'        => Type::listOf(GraphQL::type('Report')),
                'description' => '举报列表',
                'resolve'     => function ($root, $args) {
                    return $root->reports();
                },
            ],

            'has_image'       => [
                'type'        => Type::boolean(),
                'description' => 'is has image ...',
                'resolve'     => function ($root, $args) {
                    return $root->hasImage();
                },
            ],
            'pivot_time_ago'  => [
                'type'        => Type::string(),
                'description' => 'article pivot category ...',
                'resolve'     => function ($root, $args) {
                    return diffForHumansCN($root->pivot->updated_at);
                },
            ],
            'pivot_category'  => [
                'type'        => GraphQL::type('Category'),
                'description' => 'article pivot category ...',
                'resolve'     => function ($root, $args) {
                    return Category::find($root->pivot->category_id);
                },
            ],
            'pivot_status'    => [
                'type'        => Type::string(),
                'description' => 'article pivot category pivot status ...',
                'resolve'     => function ($root, $args) {
                    return $root->pivot->submit;
                },
            ],
            'submited_status' => [
                'type'        => Type::string(),
                'description' => 'submited_status ...',
                'resolve'     => function ($root, $args) {
                    return $root->submited_status;
                },
            ],
            'submit_status'   => [
                'type'        => Type::string(),
                'description' => 'submit_status ...',
                'resolve'     => function ($root, $args) {
                    return $root->submit_status;
                },
            ],
            'liked'           => [
                'type'        => Type::boolean(),
                'description' => 'is liked this article ...',
                'resolve'     => function ($root, $args) {
                    $user = session('user');
                    if ($user) {
                        return $user->isLiked('articles', $root->id);
                    }
                },
            ],

            //counts ...

            'hits'            => [
                'type'        => Type::int(),
                'description' => 'The hits of article',
            ],
            'count_comments'  => [
                'type'        => Type::int(),
                'description' => 'The count comments of article',
                'resolve'     => function ($root, $args) {
                    return $root->count_comments ? $root->count_comments : 0;
                },
            ],
            'count_reports'   => [
                'type'        => Type::int(),
                'description' => 'The count reports of article',
            ],
            'count_replies'   => [
                'type'        => Type::int(),
                'description' => 'The count replies of article',
            ],
            'count_tips'      => [
                'type'        => Type::int(),
                'description' => 'The count tips of article',
            ],
            'count_likes'     => [
                'type'        => Type::int(),
                'description' => 'The count_likes of article',
            ],
            'count_words'     => [
                'type'        => Type::int(),
                'description' => 'The count_words of article',
            ],
            'time_ago'        => \App\GraphQL\Field\TimeField::class,
            'updated_at'      => \App\GraphQL\Field\UpdatedAtField::class,

            //relations ...
            'collection'      => [
                'type'        => GraphQL::type('Collection'),
                'description' => 'collection of article',
                'resolve'     => function ($root, $args) {
                    return $root->collection;
                },
            ],
            'categories'      => [
                'type'        => Type::listOf(GraphQL::type('Category')),
                'description' => 'categories of article',
                'args'        => [
                    'offset' => ['name' => 'offset', 'type' => Type::int()],
                    'limit'  => ['name' => 'limit', 'type' => Type::int()],
                ],
                'resolve'     => function ($root, $args) {
                    $qb = $root->categories();

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
            'tipedUsers'      => [
                'type'        => Type::listOf(GraphQL::type('User')),
                'description' => 'tiped users of article',
                'args'        => [
                    'offset' => ['name' => 'offset', 'type' => Type::int()],
                    'limit'  => ['name' => 'limit', 'type' => Type::int()],
                ],
                'resolve'     => function ($root, $args) {
                    $qb = $root->tips();

                    if (isset($args['offset'])) {
                        $qb = $qb->skip($args['offset']);
                    }
                    $limit = 10;
                    if (isset($args['limit'])) {
                        $limit = $args['limit'];
                    }
                    $qb = $qb->take($limit);

                    $users = [];
                    foreach ($qb->get() as $tip) {
                        $users[] = $tip->user;
                    }
                    return $users;
                }
            ],
            'comments'        => [
                'type'        => Type::listOf(GraphQL::type('Comment')),
                'description' => 'comments of article',
                'resolve'     => function ($root, $args) {
                    return $root->comments;
                },
            ],
            'user'            => [
                'type'        => GraphQL::type('User'),
                'description' => 'author of article',
                'resolve'     => function ($root, $args) {
                    return $root->user;
                },
            ],
            'category'        => [
                'type'        => GraphQL::type('Category'),
                'description' => 'category of article',
                'resolve'     => function ($root, $args) {
                    return $root->category;
                },
            ],
        ];
    }

}
