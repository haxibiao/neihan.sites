<?php

namespace Tests\Feature\GraphQL;

use App\Comment;
use App\Post;
use App\Spider;
use App\User;
use App\Verify;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;

class Ios1v1Test extends GraphQLTestCase
{
    use DatabaseTransactions;
    /* --------------------------------------------------------------------- */
    /* ------------------------------- Query ----------------------------- */
    /* --------------------------------------------------------------------- */
    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testPostsQuery
     */
    public function testPostsQuery(){
        $user = User::find(1);
        $query   = file_get_contents(__DIR__ . '/ios1v1/PostsQuery.gql');
        $headers = [];
        $variables = [
            'user_id' => $user->id,
        ];
        $this->startGraphQL($query, $variables, $headers);
    }
    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testPublicPostsQuery
     */
    public function testPublicPostsQuery(){
        $user = User::find(1);
        $query   = file_get_contents(__DIR__ . '/ios1v1/PublicPostsQuery.gql');
        $headers = [];
        $variables = [
            'user_id' => $user->id,
        ];
        $this->startGraphQL($query, $variables, $headers);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testPublicPostsQuery
     */
    public function testRecommendPostsQuery(){
        $token = User::find(1)->api_token;
        $query   = file_get_contents(__DIR__ . '/ios1v1/RecommendPostsQuery.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        $this->startGraphQL($query, [], $headers);

        $this->startGraphQL($query, [], []);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testRecommendVideosQuery
     */
    public function testRecommendVideosQuery(){
        $token = User::find(1)->api_token;
        $query   = file_get_contents(__DIR__ . '/ios1v1/RecommendVideosQuery.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        $this->startGraphQL($query, [], $headers);
        $this->startGraphQL($query, [], []);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testSearchPostQuery
     */
    public function testSearchPostQuery(){
        $query   = file_get_contents(__DIR__ . '/ios1v1/SearchPostQuery.gql');
        $variables = [
            'query' => '美女',
        ];
        $this->startGraphQL($query, $variables, []);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testShareQuery
     */
    public function testShareQuery(){
        $post = Post::has('video')->first();
        $query   = file_get_contents(__DIR__ . '/ios1v1/ShareQuery.gql');
        $variables = [
            'id' => $post->id,
        ];
        $this->startGraphQL($query, $variables, []);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testUserLikedArticlesQuery
     */
    public function testUserLikedArticlesQuery(){
        $user = User::find(1);
        $query   = file_get_contents(__DIR__ . '/ios1v1/UserLikedArticlesQuery.gql');
        $variables = [
            'user_id' => $user->id,
        ];
        $this->startGraphQL($query, $variables, []);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testUserPostsQuery
     */
    public function testUserPostsQuery(){
        $user = User::find(1);
        $query   = file_get_contents(__DIR__ . '/ios1v1/UserPostsQuery.gql');
        $variables = [
            'user_id' => $user->id,
        ];
        $this->startGraphQL($query, $variables, []);
    }
    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testUserFavoriteArticlesQuery
     */
    public function testUserFavoriteArticlesQuery(){
        $user = User::find(1);
        $query   = file_get_contents(__DIR__ . '/ios1v1/UserFavoriteArticlesQuery.gql');
        $variables = [
            'user_id' => $user->id,
        ];
        $this->startGraphQL($query, $variables, []);
    }
    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testUserVisitsQuery
     */
    public function testUserVisitsQuery(){
        $user = User::find(1);
        $query   = file_get_contents(__DIR__ . '/ios1v1/UserVisitsQuery.gql');
        $variables = [
            'user_id' => $user->id,
        ];
        $this->startGraphQL($query, $variables, []);
    }
    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testMeMetaQuery
     */
    public function testMeMetaQuery(){
        $user = User::find(1);
        $query   = file_get_contents(__DIR__ . '/ios1v1/MeMetaQuery.gql');
        $variables = [];
        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ];
        $this->startGraphQL($query, $variables, $headers);
    }
    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testUserProfileQuery
     */
    public function testUserProfileQuery(){
        $user = User::find(1);
        $query   = file_get_contents(__DIR__ . '/ios1v1/UserProfileQuery.gql');
        $variables = [
            'id' => $user->id
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ];
        $this->startGraphQL($query, $variables, $headers);
    }
    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testShowUserBlockQuery
     */
    public function testShowUserBlockQuery(){
        $user = User::find(1);
        $query   = file_get_contents(__DIR__ . '/ios1v1/ShowUserBlockQuery.gql');
        $variables = [
            'user_id' => $user->id
        ];
        $this->startGraphQL($query, $variables, []);
    }
    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testUserQuery
     */
    public function testUserQuery(){
        $user = User::find(1);
        $query   = file_get_contents(__DIR__ . '/ios1v1/UserQuery.gql');
        $variables = [
            'id' => $user->id
        ];
        $this->startGraphQL($query, $variables, []);
    }
    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testFollowedUsersQuery
     */
    public function testFollowedUsersQuery(){
        $user = User::find(1);
        $query   = file_get_contents(__DIR__ . '/ios1v1/FollowedUsersQuery.gql');
        $this->startGraphQL($query, [
            'user_id' => $user->id
        ], [
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);
    }
    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testUserFollowersQuery
     */
    public function testUserFollowersQuery(){
        $user = User::find(1);
        $query   = file_get_contents(__DIR__ . '/ios1v1/UserFollowersQuery.gql');
        $this->startGraphQL($query, [
            'user_id' => $user->id
        ], [
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);
    }
    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testFollowedArticlesQuery
     */
    public function testFollowedArticlesQuery(){
        $user = User::find(1);
        $query   = file_get_contents(__DIR__ . '/ios1v1/FollowedArticlesQuery.gql');
        $this->startGraphQL($query, [
            'user_id' => $user->id
        ], [
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);
    }
    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testMyFeedbackQuery
     */
    public function testMyFeedbackQuery(){
        $user = User::find(1);
        $query   = file_get_contents(__DIR__ . '/ios1v1/MyFeedbackQuery.gql');
        $this->startGraphQL($query, [
            'id' => $user->id
        ], [
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);
    }
    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testFeedbacksQuery
     */
    public function testFeedbacksQuery(){
        $query   = file_get_contents(__DIR__ . '/ios1v1/FeedbacksQuery.gql');
        $this->startGraphQL($query, [], []);
    }
    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testCommentsQuery
     */
    public function testCommentsQuery(){
        $comment = Comment::first();
        $query   = file_get_contents(__DIR__ . '/ios1v1/CommentsQuery.gql');
        $this->startGraphQL($query, [
            'commentable_id'    => $comment->commentable_id,
            'commentable_type'  => $comment->commentable_type,
        ], []);
    }
    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_Ios1v1Test_testCommentRepliesQuery
     */
    public function testCommentRepliesQuery(){
        $comment = Comment::first();
        $query   = file_get_contents(__DIR__ . '/ios1v1/CommentRepliesQuery.gql');
        $this->startGraphQL($query, [
            'id'    => $comment->id,
        ], []);
    }
    /* --------------------------------------------------------------------- */
    /* ------------------------------- Mutation ----------------------------- */
    /* --------------------------------------------------------------------- */
    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testAddArticleBlockMutation
     */
    public function testAddArticleBlockMutation(){
        $post = Post::find(2);
        $token = User::find(1)->api_token;
        $query   = file_get_contents(__DIR__ . '/ios1v1/AddArticleBlockMutation.gql');
        $variables = [
            'id' => $post->id,
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        $this->startGraphQL($query, $variables, $headers);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testAddArticleBlockMutation
     */
    public function testAddUserBlockMutation(){
        $token = User::find(1)->api_token;
        $blockUser = User::find(2);
        $query   = file_get_contents(__DIR__ . '/ios1v1/AddUserBlockMutation.gql');
        $variables = [
            'id' => $blockUser->id,
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        $this->startGraphQL($query, $variables, $headers);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testCreatePostContentMutation
     */
    public function testCreatePostContentMutation(){
        $token   = User::find(1)->api_token;
        $query   = file_get_contents(__DIR__ . '/ios1v1/CreatePostContentMutation.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        $image  = UploadedFile::fake()->image('photo.jpg');
        $base64 = 'data:' . $image->getMimeType() . ';base64,' . base64_encode(file_get_contents($image->getRealPath()));

        $post = Post::has('video')->first();
        $video   = $post->video;

        //情形1:创建视频动态
        $variables = [
            'video_id' => $video->id,
            'body'     => '测试创建创建视频动态',
            'type'     => 'POST',
        ];
        $this->startGraphQL($query, $variables, $headers);

        //情形2:创建带图动态
        $variables = [
            'images'       => [$base64],
            'body'         => '测试创建带图动态?',
            'type'         => 'POST',
            'category_ids' => [2],
        ];
        $this->startGraphQL($query, $variables, $headers);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testCreateReport
     */
    public function testCreateReport(){
        $token   = User::find(1)->api_token;
        $post = Post::find(2);
        $query   = file_get_contents(__DIR__ . '/ios1v1/CreateReportMutation.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        $variables = [
            'id'     => $post->id,
            'reason' => '测试一下',
            'type'   => 'posts'
        ];
        $this->startGraphQL($query, $variables, $headers);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testDeleteArticle
     */
    public function testDeleteArticle(){
        $token   = User::find(1)->api_token;
        $post = Post::find(2);
        $query   = file_get_contents(__DIR__ . '/ios1v1/DeleteArticleMutation.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        $variables = [
            'id'     => $post->id
        ];
        $this->startGraphQL($query, $variables, $headers);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testDeletePost
     */
    public function testDeletePost(){
        $token   = User::find(1)->api_token;
        $post = Post::find(2);
        $query   = file_get_contents(__DIR__ . '/ios1v1/DeletePostMutation.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        $variables = [
            'id'     => $post->id,
        ];
        $this->startGraphQL($query, $variables, $headers);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testResolveDouyinVideo
     */
    public function testResolveDouyinVideo()
    {
        //确保后面UT不重复
        Spider::where('source_url', 'https://v.douyin.com/vruTta/')->delete();
        $user = User::where("ticket", ">", 10)->first();
        $query     = file_get_contents(__DIR__ . '/ios1v1/ResolveDouyinVideoMutation.gql');
        $variables = [
            'share_link' => "#在抖音，记录美好生活#美元如何全球褥羊毛？经济危机下，2万亿救市的深层动力，你怎么看？#经济 #教育#云上大课堂 #抖音小助手 https://v.douyin.com/vruTta/ 复制此链接，打开【抖音短视频】，直接观看视频！",
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ];

        $this->runGuestGQL($query, $variables, $headers);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testToggleLike
     */
    public function testToggleLike()
    {
        $token   = User::find(1)->api_token;
        $post = Post::find(2);
        $query   = file_get_contents(__DIR__ . '/ios1v1/ToggleLikeMutation.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        $variables = [
            'liked_id'     => $post->id,
            'liked_type'   => 'POST'
        ];
        $this->startGraphQL($query, $variables, $headers);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testUpdatePostMutation
     */
    public function testUpdatePostMutation()
    {
        $token   = User::find(1)->api_token;
        $post = Post::find(2);
        $query   = file_get_contents(__DIR__ . '/ios1v1/UpdatePostMutation.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        $variables = [
            'id'      => $post->id,
            'content' => '测试',
            'description' => '测试',
            'tag_names' => ['测试','测试','测试'],
        ];
        $this->startGraphQL($query, $variables, $headers);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testSignInMutation
     */
    public function testSignInMutation()
    {
        $query   = file_get_contents(__DIR__ . '/ios1v1/SignInMutation.gql');

        $user   = User::find(1)->first();
        $this->startGraphQL($query, [
            'account'   => $user->account,
            'password'  => 'mm1122',
        ],[]);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testAutoSignInMutation
     */
    public function testAutoSignInMutation()
    {
        $query   = file_get_contents(__DIR__ . '/ios1v1/AutoSignInMutation.gql');

        // 老用户UUID
        $uuid   = User::whereNotNull('uuid')->first()->uuid;
        $this->startGraphQL($query, [
            'UUID' => $uuid
        ],[]);

        // 老用户UUID + 老用户PHONE
        $user   = User::whereNotNull('uuid')->whereNotNull('phone')->first();
        $this->startGraphQL($query,[
            'UUID' => $user->uuid,
            'PHONE' => $user->phone,
        ], []);

        // 新用户UUID
        $this->startGraphQL($query, [
            'UUID' => str_random(24),
        ],[]);

        // 新用户UUID+新用户PHONE
        $this->startGraphQL($query, [
            'UUID'  =>  str_random(24),
            'PHONE' => '17674757044',
        ],[]);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testSmsSignInMutation
     */
    public function testSmsSignInMutation()
    {
        $query   = file_get_contents(__DIR__ . '/ios1v1/SmsSignInMutation.gql');

        $user   = User::whereNotNull('phone')->latest()->first();
        $code = rand(1000, 9999);

        Verify::create([
            'user_id' => $user->id,
            'code' => $code,
            'channel' => 'sms',
            'account' => $user->phone,
            'action' => 'USER_LOGIN',
        ]);
        // 获取验证码
        $this->startGraphQL($query, [
            'code'  => $code,
            'phone' =>$user->phone,
        ],[]);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testUpdateUserNameMutation
     */
    public function testUpdateUserNameMutation()
    {
        $query   = file_get_contents(__DIR__ . '/ios1v1/UpdateUserNameMutation.gql');

        $user   = User::find(1)->first();
        $this->startGraphQL($query, [
            'id'  => $user->id,
            'input' =>[
                'name' => '测试'
            ],
        ],[
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testUpdateUserGenderMutation
     */
    public function testUpdateUserGenderMutation()
    {
        $query   = file_get_contents(__DIR__ . '/ios1v1/UpdateUserGenderMutation.gql');

        $user   = User::find(1)->first();
        $this->startGraphQL($query, [
            'id'         => $user->id,
            'gender'     => '男',
        ],[
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testUpdateUserBirthdayMutation
     */
    public function testUpdateUserBirthdayMutation()
    {
        $query   = file_get_contents(__DIR__ . '/ios1v1/UpdateUserBirthdayMutation.gql');

        $user   = User::find(1)->first();
        $this->startGraphQL($query, [
            'id'         => $user->id,
            'input'     => [
                'birthday' => '2012-12-12'
            ],
        ],[
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testUpdateUserIntroductionMutation
     */
    public function testUpdateUserIntroductionMutation()
    {
        $query   = file_get_contents(__DIR__ . '/ios1v1/UpdateUserIntroductionMutation.gql');

        $user   = User::find(1)->first();
        $this->startGraphQL($query, [
            'id'         => $user->id,
            'input'     => [
                'introduction' => '一句描述代表不了我'
            ],
        ],[
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testRetrievePasswordMutation
     */
    public function testRetrievePasswordMutation()
    {
        $query   = file_get_contents(__DIR__ . '/ios1v1/RetrievePasswordMutation.gql');
        $user   = User::whereNotNull('phone')->latest()->first();

        $code = rand(1000, 9999);
        Verify::create([
            'user_id' => $user->id,
            'code' => $code,
            'channel' => 'sms',
            'account' => $user->phone,
            'action' => 'RESET_PASSWORD',
        ]);
        $this->startGraphQL($query, [
            'code'  => $code,
            'phone' =>$user->phone,
            'newPassword' => '1122mm',
        ],[]);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testDestoryUserMutation
     */
    public function testDestoryUserMutation()
    {
        $query   = file_get_contents(__DIR__ . '/ios1v1/DestoryUserMutation.gql');
        $user   = User::first();

        $this->startGraphQL($query, [],[
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testRemoveUserBlockMutation
     */
    public function testRemoveUserBlockMutation()
    {
        $user = User::find(1);
        $blockUser = User::find(2);

        // 拉黑
        $query   = file_get_contents(__DIR__ . '/ios1v1/AddUserBlockMutation.gql');
        $variables = [
            'id' => $blockUser->id,
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ];
        $this->startGraphQL($query, $variables, $headers);

        // 取消拉黑
        $query   = file_get_contents(__DIR__ . '/ios1v1/RemoveUserBlockMutation.gql');
        $this->startGraphQL($query, [
            'id' => $blockUser->id,
        ],[
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testUnreadsQuery
     */
    public function testUnreadsQuery()
    {
        $query   = file_get_contents(__DIR__ . '/ios1v1/UnreadsQuery.gql');
        $user   = User::find(1);

        $this->startGraphQL($query, [],[
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testCommentNotificationQuery
     */
    public function testCommentNotificationQuery()
    {
        $query   = file_get_contents(__DIR__ . '/ios1v1/CommentNotificationQuery.gql');
        $user   = User::find(1);

        $this->startGraphQL($query, [],[
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testLikeNotificationsQuery
     */
    public function testLikeNotificationsQuery()
    {
        $query   = file_get_contents(__DIR__ . '/ios1v1/LikeNotificationsQuery.gql');
        $user   = User::find(1);

        $this->startGraphQL($query, [],[
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testFollowersNotificationsQuery
     */
    public function testFollowersNotificationsQuery()
    {
        $query   = file_get_contents(__DIR__ . '/ios1v1/FollowersNotificationsQuery.gql');
        $user   = User::find(1);

        $this->startGraphQL($query, [],[
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);
    }

    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testOtherNotificationsQuery
     */
    public function testOtherNotificationsQuery()
    {
        $query   = file_get_contents(__DIR__ . '/ios1v1/OtherNotificationsQuery.gql');
        $user   = User::find(1);

        $this->startGraphQL($query, [],[
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);
    }
    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testFollowMutation
     */
    public function testFollowMutation(){
        $user = User::find(1);
        $followUser = User::find(2);
        $query   = file_get_contents(__DIR__ . '/ios1v1/FollowMutation.gql');
        $this->startGraphQL($query, [
            'user_id'     => $user->id,
            'followed_id' => $followUser->id,
            'type'        => 'users',
        ], [
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);
    }
    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testUnfollowMutation
     */
    public function testUnfollowMutation(){
        $user = User::find(1);
        $followUser = User::find(2);
        $query   = file_get_contents(__DIR__ . '/ios1v1/UnfollowMutation.gql');
        $this->startGraphQL($query, [
            'user_id'     => $user->id,
            'followed_id' => $followUser->id,
            'type'        => 'users',
        ], [
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);
    }
    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testFollowUserMutation
     */
    public function testFollowUserMutation(){
        $user = User::find(1);
        $followUser = User::find(2);
        $query   = file_get_contents(__DIR__ . '/ios1v1/FollowUserMutation.gql');
        $this->startGraphQL($query, [
            'id' => $followUser->id,
        ], [
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);
        $this->startGraphQL($query, [
            'id' => $followUser->id,
        ], [
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);
    }
    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testCreateFeedbackMutation
     */
    public function testCreateFeedbackMutation(){
        $user = User::find(1);
        $query   = file_get_contents(__DIR__ . '/ios1v1/CreateFeedbackMutation.gql');
        $this->startGraphQL($query, [
            'content'     => '测试反馈',
            'images'      => [$this->getBase64ImageString()],
        ], [
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);
    }
    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testAddCommentMutation
     */
    public function testAddCommentMutation(){
        $post = Post::first();
        $user = User::first();
        $query   = file_get_contents(__DIR__ . '/ios1v1/AddCommentMutation.gql');
        $this->startGraphQL($query, [
            'commentable_id'      => $post->id,
            'commentable_type'    => 'posts',
            'body'                => '测试评论'
        ], [
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);
    }
    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testDeleteCommentMutation
     */
    public function testDeleteCommentMutation(){
        $user = User::find(1);
        $query   = file_get_contents(__DIR__ . '/ios1v1/DeleteCommentMutation.gql');
        $this->startGraphQL($query, [
            'id'      => $user->id,
        ], [
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);
    }
    /**
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testChatQuery
     */
    public function testChatQuery(){
        $user       = User::find(1);
        $withUser   = User::find(2);

        // ChatMutationQuery
        $createChatMutationQuery   = file_get_contents(__DIR__ . '/ios1v1/CreateChatMutation.gql');
        $this->startGraphQL($createChatMutationQuery, [
            'id'      => $withUser->id,
        ], [
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);

        // ChatsQuery
        $chatsQuery   = file_get_contents(__DIR__ . '/ios1v1/CreateChatMutation.gql');
        $content = $this->startGraphQL($chatsQuery, [
            'id'      => $withUser->id,
        ], [
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);
        $chatId = data_get(json_decode($content->getContent()),'data.createChat.id');

        // SendMessageMutation
        $sendMessageMutationQuery   = file_get_contents(__DIR__ . '/ios1v1/SendMessageMutation.gql');
        $this->startGraphQL($sendMessageMutationQuery, [
            'user_id'      => $user->id,
            'chat_id'      => $chatId,
            'message'      => '测试消息发送',
        ], [
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);

        // MessagesQuery
        $messagesQuery   = file_get_contents(__DIR__ . '/ios1v1/MessagesQuery.gql');
        $this->startGraphQL($messagesQuery, [
            'chat_id'      => $chatId,
        ], [
            'Authorization' => 'Bearer ' . $user->api_token,
            'Accept'        => 'application/json',
        ]);
    }

    /* --------------------------------------------------------------------- */
    /* ----------------------------- RestFul Api --------------------------- */
    /* --------------------------------------------------------------------- */

    /**
     * 修改头像
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testSaveAvatar
     */
    public function testSaveAvatar(){
        $user = \App\User::first();
        $image  = UploadedFile::fake()->image('photo.jpg');
        $base64 = 'data:' . $image->getMimeType() . ';base64,' . base64_encode(file_get_contents($image->getRealPath()));

        // 普通文件流
        $response = $this->post("/api/user/save-avatar", [
            'api_token' => $user->api_token,
            'avatar'    => $image
        ])->assertStatus(200);

        // Base64
        $response = $this->post('/api/user/save-avatar', [
            'api_token' => $user->api_token,
            'avatar'    => $base64
        ])->assertStatus(200);
    }

    /**
     * 分享页面
     * @group  Ios1v1Test
     * @group  Ios1v1Test_testSharePost
     */
    public function testSharePost(){
        $post = Post::has('video')->first();

        $url = sprintf('/share/post/%d',$post->id);

        $this->get($url)
            ->assertStatus(200);
    }
}
