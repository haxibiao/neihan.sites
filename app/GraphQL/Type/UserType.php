<?php

namespace App\GraphQL\Type;

use App\Article;
use App\Category;
use App\Visit;
use DB;
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
            'error'              => [
                'type'        => Type::string(),
                'description' => 'error message',
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
                    return is_array($root) ? $root['avatar'] : $root->avatar();
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
                'resolve'     => function ($root, $args) {                 
                    
                    return $root->introduction();
                },
            ],
            'tip_words'         => [
                'type'        => Type::string(),
                'description' => 'tip_words of user',
            ],
            'time_ago'          => \App\GraphQL\Field\TimeField::class,

            //counts
            'count_reports'     => [
                'type'        => Type::int(),
                'description' => 'The count reports of article',
            ],
            //阅读统计
            "today_read_rate"   => [
                'type'        => Type::string(),
                'description' => 'The rate of the Visit',
                'resolve'     => function ($root, $args) {
                    $all_visits_today    = Visit::whereDay('updated_at', date('d'))->count();
                    $current_user_visits = Visit::whereDay('updated_at', date('d'))->where('user_id', $root->id)->count();
                    if (!$all_visits_today) {
                        $all_visits_today = 1;
                    }
                    $rate = $current_user_visits / $all_visits_today;
                    return round($rate * 100) . "%";
                },
            ],
            //今日阅读字数
            'today_read_num'    => [
                'type'        => Type::int(),
                'description' => 'The today_read_num of the Visit',
                'resolve'     => function ($root, $args) {
                    $visited_article_ids = Visit::whereDay('updated_at', date('d'))->where('visited_type', 'articles')->pluck('id');
                    $total_sum           = Article::whereIn('id', $visited_article_ids)->sum('count_words');
                    return $total_sum;
                },
            ],
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
                'resolve'     => function ($root, $args) {
                    return Article::where('source_url', '=', '0')
                        ->where('status', '>', 0 )
                        ->where('user_id',$root->id)
                        ->get()->count();
                },
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
                'resolve'     => function ($root, $args) {
                    return $root->categories()->count();
                },
            ],
            'balance'           => [
                'type'        => Type::float(),
                'description' => 'balance of user',
                'resolve'     => function ($root, $args) {
                    return $root->balance();
                },
            ],

            //computed

            'reports'           => [
                'type'        => Type::listOf(GraphQL::type('Report')),
                'description' => '举报列表',
                'resolve'     => function ($root, $args) {
                    return $root->reports();
                },
            ],

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

            'blockedUsers'      => [
                'type'        => Type::listOf(GraphQL::type('User')),
                'description' => 'blocked users ',
                'resolve'     => function ($root, $args) {
                    return $root->blockedUsers();
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
                        foreach ($root->adminCategories as $category) {
                            foreach ($category->newRequestArticles as $article) {
                                $articles[] = $article;
                            }
                        }
                        return $articles;
                    }
                    //屏蔽爬虫文章
                    $qb = $root->articles()->where('source_url','0'); 
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
                    'offset' => ['name' => 'offset', 'type' => Type::int()],
                    'limit'  => ['name' => 'limit', 'type' => Type::int()],
                ],
                'description' => 'user categories 包含　关注，管理的，创建的 ',
                'resolve'     => function ($root, $args) {
                    //分页参数,后面可以把自定义分页功能提取出来
                    $offset = isset($args['offset']) ? $args['offset'] : 0;
                    $limit  = isset($args['offset']) ? $args['limit'] : 10; //获取多少条数据，默认为10

                    if (isset($args['filter'])) {
                        switch ($args['filter']) {
                            //我管理的专题
                            case 'ADMIN':
                                return $root->adminCategories()
                                    ->skip($offset)
                                    ->take($limit)->get();
                            //我管理的专题中有投稿请求的专题
                            case 'REQUESTED':
                                return $root->adminCategories()->where('new_request_title', '<>', null)
                                    ->skip($offset)
                                    ->take($limit)
                                    ->get();
                            //我关注的专题
                            case 'FOLLOWED':
                                $categories           = [];
                                $following_categories = $root->followings()
                                    ->where('followed_type', 'categories')
                                    ->skip($offset)
                                    ->take($limit)
                                    ->get();
                                foreach ($following_categories as $following) {
                                    $categories[] = $following->followed;
                                }
                                return $categories;
                            //我最近投稿的专题
                            case 'LATEST_REQUEST':
                                return $root->categories()
                                    ->orderBy('created_at', 'desc')
                                    ->skip($offset)
                                    ->take($limit)
                                    ->get();
                            //推荐专题
                            case 'RECOMMEND':
                                $followed_category_ids = \DB::table('follows') 
                                    ->where('user_id', $root->id)
                                    ->where('followed_type', 'categories')
                                    ->pluck('followed_id')->toArray();
                                return \App\Category::orderBy('updated_at', 'desc')
                                    ->whereNotIn('id',$followed_category_ids)
                                    ->skip($offset)
                                    ->take($limit)
                                    ->get();
                            default:
                                break;
                        }
                    }
                    return $root->categories()->skip($offset)->take($limit)->get();
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
                                ->whereIn('type', [
                                    'App\Notifications\CollectionFollowed',
                                    'App\Notifications\CategoryFollowed',
                                    'App\Notifications\ArticleApproved',
                                    'App\Notifications\ArticleRejected',
                                ]);
                            //mark as read
                            $unread_notifications = $root->unreadNotifications()
                                ->whereIn('type', [
                                    'App\Notifications\CollectionFollowed',
                                    'App\Notifications\CategoryFollowed',
                                    'App\Notifications\ArticleApproved',
                                    'App\Notifications\ArticleRejected',
                                ])->get();
                            $unread_notifications ->markAsRead();
                            break;
                        case 'GROUP_LIKES':
                            $qb = $root->notifications()->orderBy('created_at', 'desc')
                                ->whereIn('type', [
                                    'App\Notifications\ArticleLiked',
                                    'App\Notifications\CommentLiked',
                                ]);
                            //mark as read
                            $unread_notifications = $root->unreadNotifications()
                                ->whereIn('type', [
                                    'App\Notifications\ArticleLiked',
                                    'App\Notifications\CommentLiked',
                                ])->get();
                            $unread_notifications -> markAsRead();
                            break;

                        default: 
                            $qb = $root->notifications()->orderBy('created_at', 'desc')->where('type', $args['type']);
                            //mark as read 
                            $unread_notifications = $root->unreadNotifications()->where('type', $args['type'])->get();
                            $unread_notifications -> markAsRead();
                            break;
                    }
                    //$root->forgetUnreads();

                    if (isset($args['offset'])) {
                        $qb = $qb->skip($args['offset']);
                    }
                    $limit = 10;
                    if (isset($args['limit'])) {
                        $limit = $args['limit'];
                    }
                    $qb            = $qb->take($limit);
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
            'friends'           => [
                'args'        => [
                    'offset' => ['name' => 'offset', 'type' => Type::int()],
                    'limit'  => ['name' => 'limit', 'type' => Type::int()],
                ],
                'type'        => Type::listOf(GraphQL::type('User')),
                'description' => 'friends of current user ',
                'resolve'     => function ($root, $args) {
                    $friends = [];
                    foreach ($root->followingUsers as $follow) {
                        $friends[$follow->followed->id] = $follow->followed;
                    }
                    $count_need_followers = 500 - count($friends);
                    foreach ($root->follows()->take($count_need_followers)->get() as $follow) {
                        $friends[$follow->user->id] = $follow->user;
                    }
                    return $friends;
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
                    $limit = 10;
                    if (isset($args['limit'])) {
                        $limit = $args['limit'];
                    }
                    $qb = $qb->take($limit);
                    return $qb->get();
                },
            ],

            'submitedArticles'  => [
                'args'        => [
                    'offset' => ['name' => 'offset', 'type' => Type::int()],
                    'limit'  => ['name' => 'limit', 'type' => Type::int()],
                ],
                'type'        => Type::listOf(GraphQL::type('Article')),
                'description' => 'submited articles ',
                'resolve'     => function ($root, $args) {
                    $user = getUser();

                    $aids   = $root->publishedArticles()->pluck('id'); 
                    $qb     = DB::table('article_category')->whereIn('article_id', $aids);
                    $offset = isset($args['offset']) ? $args['offset'] : 0;
                    $limit  = isset($args['limit']) ? $args['limit'] : 10;
                    $qb     = $qb->skip($offset)->take($limit);

                    $articles = [];
                    foreach ($qb->get() as $pivotItem) {
                        $category                  = Category::find($pivotItem->category_id);
                        $article                   = Article::find($pivotItem->article_id);
                        $article->submitedCategory = $category;
                        $article->submited_status  = $pivotItem->submit;
                        $article->submit_status    = get_submit_status($pivotItem->submit, $category->isAdmin($user));
                        $articles[]                = $article;
                    }
                    return $articles;
                },
            ],

            'incomeHistory'     => [
                'type'        => Type::listOf(Type::string()),
                'description' => 'user last 4 month income summary ....',
                'resolve'     => function ($root, $args) {
                    $qb = $root->transactions()
                        ->where('to_user_id', $root->id) // amount to me is income
                        ->where('created_at', '>', \Carbon\Carbon::now()->addDays(-5 * 30));
                    $current_month        = \Carbon\Carbon::now()->month;
                    $current_year         = \Carbon\Carbon::now()->year;
                    $sum_1                = $qb->whereMonth('created_at', $current_month)->sum('amount');
                    $last_month           = \Carbon\Carbon::now()->addMonths(-1)->month;
                    $last_month_year      = \Carbon\Carbon::now()->addMonths(-1)->year;
                    $sum_2                = $qb->whereMonth('created_at', $last_month)->sum('amount');
                    $two_month_ago        = \Carbon\Carbon::now()->addMonths(-2)->month;
                    $two_month_ago_year   = \Carbon\Carbon::now()->addMonths(-2)->year;
                    $sum_3                = $qb->whereMonth('created_at', $two_month_ago)->sum('amount');
                    $three_month_ago      = \Carbon\Carbon::now()->addMonths(-3)->month;
                    $three_month_ago_year = \Carbon\Carbon::now()->addMonths(-3)->year;
                    $sum_4                = $qb->whereMonth('created_at', $three_month_ago)->sum('amount');

                    return [
                        "$current_year 年$current_month 月,$sum_1",
                        "$last_month_year 年$last_month 月,$sum_2",
                        "$two_month_ago_year 年$two_month_ago 月,$sum_3",
                        "$three_month_ago_year 年$three_month_ago 月,$sum_4",
                    ];
                },
            ],
        ];
    }

}
