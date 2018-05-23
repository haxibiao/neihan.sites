<?php

return [

    /*
     * The prefix for routes
     */
    'prefix'                => 'graphql',

    /*
     * The domain for routes
     */
    'domain'                => null,

    /*
     * The routes to make GraphQL request. Either a string that will apply
     * to both query and mutation or an array containing the key 'query' and/or
     * 'mutation' with the according Route
     *
     * Example:
     *
     * Same route for both query and mutation
     *
     * 'routes' => [
     *     'query' => 'query/{graphql_schema?}',
     *     'mutation' => 'mutation/{graphql_schema?}',
     *      mutation' => 'graphiql'
     * ]
     *
     * you can also disable routes by setting routes to null
     *
     * 'routes' => null,
     */
    'routes'                => '{graphql_schema?}',

    /*
     * The controller to use in GraphQL requests. Either a string that will apply
     * to both query and mutation or an array containing the key 'query' and/or
     * 'mutation' with the according Controller and method
     *
     * Example:
     *
     * 'controllers' => [
     *     'query' => '\Folklore\GraphQL\GraphQLController@query',
     *     'mutation' => '\Folklore\GraphQL\GraphQLController@mutation'
     * ]
     */
    'controllers'           => \Folklore\GraphQL\GraphQLController::class . '@query',

    /*
     * The name of the input variable that contain variables when you query the
     * endpoint. Most libraries use "variables", you can change it here in case you need it.
     * In previous versions, the default used to be "params"
     */
    'variables_input_name'  => 'variables',

    /*
     * Any middleware for the 'graphql' route group
     */
    'middleware'            => [
        \Illuminate\Session\Middleware\StartSession::class,
        \App\Http\Middleware\GraphQLAuth::class,
    ],

    /**
     * Any middleware for a specific 'graphql' schema
     */
    'middleware_schema'     => [
        'default' => [],
    ],

    /*
     * Any headers that will be added to the response returned by the default controller
     */
    'headers'               => [],

    /*
     * Any JSON encoding options when returning a response from the default controller
     * See http://php.net/manual/function.json-encode.php for the full list of options
     */
    'json_encoding_options' => 0,

    /*
     * Config for GraphiQL (see (https://github.com/graphql/graphiql).
     * To disable GraphiQL, set this to null
     */
    'graphiql'              => [
        'routes'     => '/graphiql/{graphql_schema?}',
        'controller' => \Folklore\GraphQL\GraphQLController::class . '@graphiql',
        'middleware' => [],
        'view'       => 'graphql::graphiql',
        'composer'   => \Folklore\GraphQL\View\GraphiQLComposer::class,
    ],

    /*
     * The name of the default schema used when no arguments are provided
     * to GraphQL::schema() or when the route is used without the graphql_schema
     * parameter
     */
    'schema'                => 'default',

    /*
     * The schemas for query and/or mutation. It expects an array to provide
     * both the 'query' fields and the 'mutation' fields. You can also
     * provide an GraphQL\Type\Schema object directly.
     *
     * Example:
     *
     * 'schemas' => [
     *     'default' => new Schema($config)
     * ]
     *
     * or
     *
     * 'schemas' => [
     *     'default' => [
     *         'query' => [
     *              'users' => 'App\GraphQL\Query\UsersQuery'
     *          ],
     *          'mutation' => [
     *
     *          ]
     *     ]
     * ]
     */
    'schemas'               => [
        'default' => [
            'query'    => [
                'user'        => '\App\GraphQL\Query\UserQuery',
                'users'       => '\App\GraphQL\Query\UsersQuery',
                'chat'        => '\App\GraphQL\Query\ChatQuery',
                'messages'    => '\App\GraphQL\Query\MessagesQuery',
                'comments'    => '\App\GraphQL\Query\CommentsQuery',
                'articles'    => '\App\GraphQL\Query\ArticlesQuery',
                'article'     => '\App\GraphQL\Query\ArticleQuery',
                'categories'  => '\App\GraphQL\Query\CategoriesQuery',
                'category'    => '\App\GraphQL\Query\CategoryQuery',
                'collections' => '\App\GraphQL\Query\CollectionsQuery',
                'collection'  => '\App\GraphQL\Query\CollectionQuery',
                'actions'     => '\App\GraphQL\Query\ActionsQuery',
                'questions'   => '\App\GraphQL\Query\QuestionsQuery',
                'follows'   => '\App\GraphQL\Query\FollowsQuery',
            ],
            'mutation' => [
                'signUp'                 => '\App\GraphQL\Mutation\user\signUpMutation',
                'signIn'                 => '\App\GraphQL\Mutation\user\signInMutation',
                'updateUserEmail'        => '\App\GraphQL\Mutation\user\updateUserEmailMutation',
                'updateUserPassword'     => '\App\GraphQL\Mutation\user\updateUserPasswordMutation',
                'updateUserName'         => '\App\GraphQL\Mutation\user\updateUserNameMutation',
                'updateUserIntroduction' => '\App\GraphQL\Mutation\user\updateUserIntroductionMutation',

                //article
                'createArticle'             => '\App\GraphQL\Mutation\article\createArticleMutation',
                'editArticle'             => '\App\GraphQL\Mutation\article\editArticleMutation',
                'removeArticle'             => '\App\GraphQL\Mutation\article\removeArticleMutation',
                'deleteArticle'             => '\App\GraphQL\Mutation\article\deleteArticleMutation',
                'restoreArticle'             => '\App\GraphQL\Mutation\article\restoreArticleMutation',
                'submitArticle'             => '\App\GraphQL\Mutation\article\submitArticleMutation',
                'approveArticle'             => '\App\GraphQL\Mutation\article\approveArticleMutation',

                //comment
                'addComment'             => '\App\GraphQL\Mutation\comment\addCommentMutation',

                //like
                'likeArticle'            => '\App\GraphQL\Mutation\like\likeArticleMutation',
                'likeComment'            => '\App\GraphQL\Mutation\like\likeCommentMutation',

                //follow
                'followCollection'       => '\App\GraphQL\Mutation\follow\followCollectionMutation',
                'followCategory'       => '\App\GraphQL\Mutation\follow\followCategoryMutation',
                'followUser'       => '\App\GraphQL\Mutation\follow\followUserMutation',

                //collection
                'createCollection'=>'App\GraphQL\Mutation\collection\createCollectionMutation',
                'editCollection'=>'App\GraphQL\Mutation\collection\editCollectionMutation',
                'deleteCollection'=>'App\GraphQL\Mutation\collection\deleteCollectionMutation',

                //category
                'createCategory'=>'App\GraphQL\Mutation\category\createCategoryMutation',
                'editCategory'=>'App\GraphQL\Mutation\category\editCategoryMutation',
                'deleteCategory'=>'App\GraphQL\Mutation\category\deleteCategoryMutation',
                'editCategoryAdmins'=>'App\GraphQL\Mutation\category\editCategoryAdminsMutation',

                //chat
                'createChat'             => '\App\GraphQL\Mutation\chat\createChatMutation',
                'sendMessage'            => '\App\GraphQL\Mutation\chat\sendMessageMutation',
            ],
        ],
    ],

    /*
     * Additional resolvers which can also be used with shorthand building of the schema
     * using \GraphQL\Utils::BuildSchema feature
     *
     * Example:
     *
     * 'resolvers' => [
     *     'default' => [
     *         'echo' => function ($root, $args, $context) {
     *              return 'Echo: ' . $args['message'];
     *          },
     *     ],
     * ],
     */
    'resolvers'             => [
        'default' => [
        ],
    ],

    /*
     * Overrides the default field resolver
     * Useful to setup default loading of eager relationships
     *
     * Example:
     *
     * 'defaultFieldResolver' => function ($root, $args, $context, $info) {
     *     // take a look at the defaultFieldResolver in
     *     // https://github.com/webonyx/graphql-php/blob/master/src/Executor/Executor.php
     * },
     */
    'defaultFieldResolver'  => null,

    /*
     * The types available in the application. You can access them from the
     * facade like this: GraphQL::type('user')
     *
     * Example:
     *
     * 'types' => [
     *     'user' => 'App\GraphQL\Type\UserType'
     * ]
     *
     * or without specifying a key (it will use the ->name property of your type)
     *
     * 'types' =>
     *     'App\GraphQL\Type\UserType'
     * ]
     */
    'types'                 => [

        //db type
        'User'             => '\App\GraphQL\Type\UserType',
        'Article'          => '\App\GraphQL\Type\ArticleType',
        'Category'         => '\App\GraphQL\Type\CategoryType',
        'Collection'       => '\App\GraphQL\Type\CollectionType',
        'Question'         => '\App\GraphQL\Type\QuestionType',
        'Comment'          => '\App\GraphQL\Type\CommentType',
        'Follow'           => '\App\GraphQL\Type\FollowType',
        'Like'             => '\App\GraphQL\Type\LikeType',
        'Action'           => '\App\GraphQL\Type\ActionType',
        'Notification'     => '\App\GraphQL\Type\NotificationType',
        'Chat'             => '\App\GraphQL\Type\ChatType',
        'Message'          => '\App\GraphQL\Type\MessageType',
        'Tip'              => '\App\GraphQL\Type\TipType',
        'Image'            => '\App\GraphQL\Type\ImageType',

        //enum
        'CollectionFilter' => '\App\GraphQL\Enum\CollectionFilterEnum',
        'CategoryFilter'   => '\App\GraphQL\Enum\CategoryFilterEnum',
        'CategoryOrder'    => '\App\GraphQL\Enum\CategoryOrderEnum',
        'ArticleFilter'    => '\App\GraphQL\Enum\ArticleFilterEnum',
        'ArticleOrder'     => '\App\GraphQL\Enum\ArticleOrderEnum',
        'CommentFilter'    => '\App\GraphQL\Enum\CommentFilterEnum',
        'CommentOrder'     => '\App\GraphQL\Enum\CommentOrderEnum',
        'UserFilter'       => '\App\GraphQL\Enum\UserFilterEnum',
        'NotificationType' => '\App\GraphQL\Enum\NotificationTypeEnum',
        'FollowFilter' => '\App\GraphQL\Enum\FollowFilterEnum',
    ],

    /*
     * This callable will receive all the Exception objects that are caught by GraphQL.
     * The method should return an array representing the error.
     *
     * Typically:
     *
     * [
     *     'message' => '',
     *     'locations' => []
     * ]
     */
    'error_formatter'       => [\Folklore\GraphQL\GraphQL::class, 'formatError'],

    /*
     * Options to limit the query complexity and depth. See the doc
     * @ https://github.com/webonyx/graphql-php#security
     * for details. Disabled by default.
     */
    'security'              => [
        'query_max_complexity'  => null,
        'query_max_depth'       => null,
        'disable_introspection' => false,
    ],
];
