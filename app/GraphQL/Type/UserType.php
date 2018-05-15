<?php

namespace App\GraphQL\Type;

use Folklore\GraphQL\Support\Facades\GraphQL;
use Folklore\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'User',
        'description' => 'A user',
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */
    // protected $inputObject = true;

    public function fields()
    {
        return [
            'id'                => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'The id of the user',
            ],
            'name'              => [
                'type'        => Type::string(),
                'description' => 'nick name',
            ],
            'email'             => [
                'type'        => Type::string(),
                'description' => 'The email of user',
            ],
            'avatar'            => [
                'type'        => Type::string(),
                'description' => 'avatar url of user',
                'resolve'     => function ($root, $args) {
                    return $root->avatar();
                },
            ],
            'token'             => [
                'type'        => Type::string(),
                'description' => 'api_token of user',
                'resolve'     => function ($root, $args) {
                    return $root->api_token;
                },
            ],
            'introduction'      => [
                'type'        => Type::string(),
                'description' => 'introduction of user',
            ],
            'tip_words'         => [
                'type'        => Type::string(),
                'description' => 'tip_words of user',
            ],
            'time_ago'          => \App\GraphQL\Field\TimeField::class,

            //counts
            'count_words'       => [
                'type'        => Type::int(),
                'description' => 'count_words of user',
            ],
            'count_likes'       => [
                'type'        => Type::int(),
                'description' => 'count_likes of user',
            ],
            'count_articles'    => [
                'type'        => Type::int(),
                'description' => 'count_articles of user',
            ],
            'count_follows'     => [
                'type'        => Type::int(),
                'description' => 'count_follows of user',
            ],
            'count_followers'   => [
                'type'        => Type::int(),
                'description' => 'count_followers of user',
                'resolve'     => function ($root, $args) {
                    //TODO:: fix this
                    return $root->count_follows;
                },
            ],
            'count_followings'  => [
                'type'        => Type::int(),
                'description' => 'count_followings of user',
            ],
            'count_drafts'      => [
                'type'        => Type::int(),
                'description' => 'count_drafts of user',
            ],
            'count_unpublishs'  => [
                'type'        => Type::int(),
                'description' => 'count_unpublishs of user',
            ],
            'count_favorites'   => [
                'type'        => Type::int(),
                'description' => 'count_favorites of user',
            ],
            'count_collections' => [
                'type'        => Type::int(),
                'description' => 'count_collections of user',
            ],
            'count_categories'  => [
                'type'        => Type::int(),
                'description' => 'count_categories of user',
            ],
            'balance'           => [
                'type'        => Type::int(),
                'description' => 'balance of user',
                'resolve'     => function ($root, $args) {
                    return $root->balance();
                },
            ],

            //computed

            'followed_status'   => [
                'type'        => Type::int(),
                'description' => 'followed status: 0 = 未关注 1=已关注 2=互相关注 ',
                'resolve'     => function ($root, $args) {
                    if ($user = session('user')) {
                        if ($user->isFollow('users', $root->id)) {
                            if ($root->followingUsers()->where('followed_id', $user->id)->count()) {
                                return 2;
                            }
                            return 1;
                        }
                    }
                    return 0;
                },
            ],

            //unreads
            'unread_comments'   => [
                'type'        => Type::int(),
                'description' => 'comments unreads',
                'resolve'     => function ($root, $args) {
                    return $root->unreads('comments');
                },
            ],
            'unread_chats'      => [
                'type'        => Type::string(),
                'description' => 'chats unreads',
                'resolve'     => function ($root, $args) {
                    return $root->unreads('chats');
                },
            ],
            'unread_requests'   => [
                'type'        => Type::string(),
                'description' => 'requests unreads',
                'resolve'     => function ($root, $args) {
                    return $root->unreads('requests');
                },
            ],
            'unread_likes'      => [
                'type'        => Type::string(),
                'description' => 'likes unreads',
                'resolve'     => function ($root, $args) {
                    return $root->unreads('likes');
                },
            ],
            'unread_follows'    => [
                'type'        => Type::string(),
                'description' => 'follows unreads',
                'resolve'     => function ($root, $args) {
                    return $root->unreads('follows');
                },
            ],
            'unread_tips'       => [
                'type'        => Type::string(),
                'description' => 'tips unreads',
                'resolve'     => function ($root, $args) {
                    return $root->unreads('tips');
                },
            ],
            'unread_others'     => [
                'type'        => Type::string(),
                'description' => 'others unreads',
                'resolve'     => function ($root, $args) {
                    return $root->unreads('others');
                },
            ],

            //relations ...

            'articles'          => [
                'args'        => [
                    'filter'      => ['name' => 'filter', 'type' => GraphQL::type('ArticleFilter')],
                    'category_id' => ['name' => 'category_id', 'type' => Type::int()],
                    'offset'      => ['name' => 'offset', 'type' => Type::int()],
                    'limit'       => ['name' => 'limit', 'type' => Type::int()],
                ],
                'type'        => Type::listOf(GraphQL::type('Article')),
                'description' => 'articles of user',
                'resolve'     => function ($root, $args) {
                    if (isset($args['filter']) && $args['filter'] == 'NEW_REQUESTED') {
                        $articles = [];                      
                        foreach($root->adminCategories as $category) {
                            foreach($category->newRequestArticles as $article) {
                                $articles[] = $article;
                            }
                        }
                        return $articles;
                    }

                    $qb = $root->articles();
                    if (isset($args['filter']) && $args['filter'] == 'TRASH') {
                        $qb = $root->removedArticles();
                    }
                    if (isset($args['filter']) && $args['filter'] == 'DRAFTS') {
                        $qb = $root->drafts();
                    }
                    if (isset($args['filter']) && $args['filter'] == 'FAVED') {
                        $qb = $root->favorites();
                    }
                    if (isset($args['filter']) && $args['filter'] == 'LIKED') {
                        $qb = $root->likes();
                    }


                    if (isset($args['offset'])) {
                        $qb = $qb->skip($args['offset']);
                    }
                    $limit = 10;
                    if (isset($args['limit'])) {
                        $limit = $args['limit'];
                    }
                    $qb = $qb->take($limit);
                    if (isset($args['filter']) && $args['filter'] == 'FAVED') {
                        $articles = [];
                        foreach ($qb->get() as $fav) {
                            $articles[] = $fav->faved;
                        }
                        return $articles;
                    }
                    if (isset($args['filter']) && $args['filter'] == 'LIKED') {
                        $articles = [];
                        foreach ($qb->get() as $like) {
                            $articles[] = $like->liked;
                        }
                        return $articles;
                    }
                    $articles = $qb->get();
                    if (isset($args['filter']) && $args['filter'] == 'CATE_SUBMIT_STATUS' && isset($args['category_id'])) {
                        $category = \App\Category::findOrFail($args['category_id']);
                        foreach ($articles as $article) {
                            $submited_status = '';
                            $query           = $article->allCategories()->wherePivot('category_id', $category->id);
                            if ($query->count()) {
                                $submited_status = $query->first()->pivot->submit;
                            }
                            $article->submited_status = $submited_status;
                            $isAdmin                  = session('user') ? $category->isAdmin(session('user')) : false;
                            $article->submit_status   = get_submit_status($submited_status, $isAdmin);
                        }
                    }
                    return $articles;
                },
            ],

            'categories'        => [
                'type'        => Type::listOf(GraphQL::type('Category')),
                'args'        => [
                    'filter' => ['name' => 'filter', 'type' => GraphQL::type('CategoryFilter')],
                ],
                'description' => 'user categories 包含　关注，管理的，创建的 ',
                'resolve'     => function ($root, $args) {
                    if (isset($args['filter']) && $args['filter'] == 'ADMIN') {
                        return $root->adminCategories;
                    }
                    if (isset($args['filter']) && $args['filter'] == 'REQUESTED') {
                        return $root->adminCategories()->where('new_requests', '>', 0)->get();
                    }
                    if (isset($args['filter']) && $args['filter'] == 'FOLLOWED') {
                        $categories           = [];
                        $following_categories = $root->followings()->where('followed_type', 'categories')->get();
                        foreach ($following_categories as $following) {
                            $categories[] = $following->followed;
                        }
                        return $categories;
                    }
                    return $root->categories;
                },
            ],

            'collections'       => [
                'type'        => Type::listOf(GraphQL::type('Collection')),
                'args'        => [
                    'filter' => ['name' => 'filter', 'type' => GraphQL::type('CollectionFilter')],
                ],
                'description' => 'user collections　包含关注的，创建的',
                'resolve'     => function ($root, $args) {
                    if (isset($args['filter']) && $args['filter'] == 'FOLLOWED') {
                        $collections           = [];
                        $following_collections = $root->followings()->where('followed_type', 'collections')->get();
                        foreach ($following_collections as $following) {
                            $collections[] = $following->followed;
                        }
                        return $collections;
                    }
                    return $root->collections()->orderBy('id', 'desc')->get();
                },
            ],

            'notifications'     => [
                'type'        => Type::listOf(GraphQL::type('Notification')),
                'args'        => [
                    'limit'       => ['name' => 'limit', 'type' => Type::int()],
                    'offset'      => ['name' => 'offset', 'type' => Type::int()],
                    'category_id' => ['name' => 'category_id', 'type' => Type::int()],
                    'type'        => ['name' => 'type', 'type' => GraphQL::type('NotificationType')],
                ],
                'description' => '用户的通知',
                'resolve'     => function ($root, $args) {
                    switch ($args['type']) {
                        case 'GROUP_OTHERS':
                            $qb = $root->notifications()->orderBy('created_at', 'desc')
                                ->where('type', 'App\Notifications\CollectionFollowed')
                                ->orWhere('type', 'App\Notifications\CategoryFollowed')
                                ->orWhere('type', 'App\Notifications\ArticleApproved')
                                ->orWhere('type', 'App\Notifications\ArticleRejected');
                            //mark as read
                            $unread_notifications = $root->unreadNotifications()
                                ->where('type', 'App\Notifications\CollectionFollowed')
                                ->orWhere('type', 'App\Notifications\CategoryFollowed')
                                ->orWhere('type', 'App\Notifications\ArticleApproved')
                                ->orWhere('type', 'App\Notifications\ArticleRejected')
                                ->get();
                            foreach ($unread_notifications as $notification) {
                                $notification->markAsRead();
                            }
                            break;
                        case 'GROUP_LIKES':
                            $qb = $root->notifications()->orderBy('created_at', 'desc')
                                ->where('type', 'App\Notifications\ArticleLiked')
                                ->orWhere('type', 'App\Notifications\CommentLiked');
                            //mark as read
                            $unread_notifications = $root->unreadNotifications()->where('type', 'App\Notifications\ArticleLiked')
                                ->orWhere('type', 'App\Notifications\CommentLiked')
                                ->get();
                            foreach ($unread_notifications as $notification) {
                                $notification->markAsRead();
                            }
                            break;

                        default:
                            $qb = $root->notifications()->orderBy('created_at', 'desc')->where('type', $args['type']);
                            //mark as read
                            $unread_notifications = $root->unreadNotifications()->where('type', $args['type'])->get();
                            foreach ($unread_notifications as $notification) {
                                $notification->markAsRead();
                            }
                            break;
                    }
                    $root->forgetUnreads();

                    if (isset($args['offset'])) {
                        $qb = $qb->skip($args['offset']);
                    }
                    $limit = 10;
                    if (isset($args['limit'])) {
                        $limit = $args['limit'];
                    }
                    $qb = $qb->take($limit);
                    $notifications = $qb->get();
                    return $notifications;
                },
            ],

            'favoritedArticles' => [
                'args'        => [
                    'offset' => ['name' => 'offset', 'type' => Type::int()],
                    'limit'  => ['name' => 'limit', 'type' => Type::int()],
                ],
                'type'        => Type::listOf(GraphQL::type('Article')),
                'description' => 'favorited articles of user',
                'resolve'     => function ($root, $args) {
                    $qb = $root->favorites();
                    if (isset($args['offset'])) {
                        $qb = $qb->skip($args['offset']);
                    }
                    $limit = 10;
                    if (isset($args['limit'])) {
                        $limit = $args['limit'];
                    }
                    $qb        = $qb->take($limit);
                    $favorites = $qb->get();
                    $articles  = [];
                    foreach ($favorites as $fav) {
                        $articles[] = $fav->faved;
                    }
                    return $articles;
                },
            ],

            'likedArticles'     => [
                'args'        => [
                    'offset' => ['name' => 'offset', 'type' => Type::int()],
                    'limit'  => ['name' => 'limit', 'type' => Type::int()],
                ],
                'type'        => Type::listOf(GraphQL::type('Article')),
                'description' => 'liked articles of user',
                'resolve'     => function ($root, $args) {
                    $qb = $root->likes();
                    if (isset($args['offset'])) {
                        $qb = $qb->skip($args['offset']);
                    }
                    $limit = 10;
                    if (isset($args['limit'])) {
                        $limit = $args['limit'];
                    }
                    $qb       = $qb->take($limit);
                    $likes    = $qb->get();
                    $articles = [];
                    foreach ($likes as $like) {
                        $articles[] = $like->liked;
                    }
                    return $articles;
                },
            ],

            'questions'         => [
                'args'        => [
                    'offset' => ['name' => 'offset', 'type' => Type::int()],
                    'limit'  => ['name' => 'limit', 'type' => Type::int()],
                ],
                'type'        => Type::listOf(GraphQL::type('Question')),
                'description' => 'questions description',
                'resolve'     => function ($root, $args) {
                    $qb = $root->questions;
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
            'followings'        => [
                'args'        => [
                    'offset' => ['name' => 'offset', 'type' => Type::int()],
                    'limit'  => ['name' => 'limit', 'type' => Type::int()],
                ],
                'type'        => Type::listOf(GraphQL::type('Follow')),
                'description' => 'follows of current user ',
                'resolve'     => function ($root, $args) {
                    $qb = $root->followings();

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
            'recommend_authors' => [
                'args'        => [
                    'offset' => ['name' => 'offset', 'type' => Type::int()],
                    'limit'  => ['name' => 'limit', 'type' => Type::int()],
                ],
                'type'        => Type::listOf(GraphQL::type('User')),
                'description' => 'recommend authors of current user ',
                'resolve'     => function ($root, $args) {
                    $qb = $root->followingUsers;

                    if (isset($args['offset'])) {
                        $qb = $qb->skip($args['offset']);
                    }
                    $limit = 10;
                    if (isset($args['limit'])) {
                        $limit = $args['limit'];
                    }
                    $qb = $qb->take($limit);

                    //TODO: count and recommend base on what you followed ...
                    $authors = [];
                    foreach ($qb->get() as $follow) {
                        $authors[] = $follow->followed;
                    }
                    return $authors;
                }
            ],

            'chats'             => [
                'args'        => [
                    'offset' => ['name' => 'offset', 'type' => Type::int()],
                    'limit'  => ['name' => 'limit', 'type' => Type::int()],
                ],
                'type'        => Type::listOf(GraphQL::type('Chat')),
                'description' => 'chats of current user ',
                'resolve'     => function ($root, $args) {
                    $qb = $root->chats();

                    if (isset($args['offset'])) {
                        $qb = $qb->skip($args['offset']);
                    }
                    $limit = 100;
                    if (isset($args['limit'])) {
                        $limit = $args['limit'];
                    }
                    $qb = $qb->take($limit);
                    return $qb->get();
                },
            ],
        ];
    }

}
