<?php
namespace App\GraphQL\Enum;

use Folklore\GraphQL\Support\Type as GraphQLType;

class NotificationTypeEnum extends GraphQLType
{
    protected $enumObject = true;

    protected $attributes = [
        'name'        => 'NotificationType',
        'description' => 'The filters of notification type',
        'values'      => [
            'ARTICLE_APPROVED'     => 'App\Notifications\ArticleApproved',
            'ARTICLE_REJECTED'     => 'App\Notifications\ArticleRejected',
            'ARTICLE_COMMENTED'    => 'App\Notifications\ArticleCommented',
            'ARTICLE_FAVORITED'    => 'App\Notifications\ArticleFavorited',
            'ARTICLE_LIKED'        => 'App\Notifications\ArticleLiked',
            'COMMENT_LIKED'        => 'App\Notifications\CommentLiked', //TODO ...
            'ARTICLE_TIPED'        => 'App\Notifications\ArticleTiped',
            'CATEGORY_FOLLOWED'    => 'App\Notifications\CategoryFollowed',
            'CATEGORY_REQUESTED'   => 'App\Notifications\CategoryRequested',
            'COLLECTION_FOLLOWED'  => 'App\Notifications\CollectionFollowed',
            'USER_FOLLOWED'        => 'App\Notifications\UserFollowed',
            'UNPROCESSED_REQUESTS' => 'UNPROCESSED_REQUESTS',
            'GROUP_LIKES'          => 'GROUP_LIKES',
            'GROUP_OTHERS'         => 'GROUP_OTHERS',
        ],
    ];
}
