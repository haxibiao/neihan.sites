<?php

namespace Tests\Feature\GraphQL;

use App\Article;
use App\Comment;
use App\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
/**
 * 该测试用例最后的更新时间为2018年7月17日19时
 */
class CommentTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * @Desc     查询评论
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testCommentsQuery(){
    	
    	$article = Article::where([
    		['status',1],
    		['count_replies','>',0]
    	])->inRandomOrder()
          ->first();
        $comment_id = null;
        $comments = $article->comments;
        if($comments->isNotEmpty()){
        	$comment_id = $comments->first()->id;
        }

        $filter = array_random([null,'ALL','ONLY_AUTHOR']);
        $order  = array_random(['LIKED_MOST','LATEST_FIRST','OLD_FIRST']); 

        $query = <<<STR
        query commentsQuery(\$article_id: Int, \$comment_id: Int, \$offset: Int, \$filter: CommentFilter, \$order: CommentOrder) {
    		  comments(article_id: \$article_id, comment_id: \$comment_id, offset: \$offset, filter: \$filter, order: \$order) {
    		    id
    		    body
    		    likes
    		    liked
    		    time_ago
    		    commentable_id
    		    lou
    		    user {
    		      id
    		      name
    		      avatar
    		    }
    		    replyComments {
    		      id
    		      body
    		      user {
    		        id
    		        name
    		      }
    		      time_ago
    		    }
    		  }
    		}
STR;
        $variables = <<<STR
        {
          "article_id"	: $article->id,
          "comment_id"	: $comment_id,
          "filter"		: $filter,
          "order" 		: $order
        }
STR;
        $response = $this->json("POST", "/graphql", [
                'query'         => $query,  
                'variables'     => $variables,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    }
    /**
     * @Desc     查询评论回复
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testReplyCommentsQuery(){
    	
    	$comment = Comment::inRandomOrder()->first();

        $query = <<<STR
        query replyCommentsQuery(\$comment_id: Int!) {
		  comments(comment_id: \$comment_id) {
		    id
		    body
		    time_ago
		    user {
		      id
		      name
		      avatar
		    }
		  }
		}
STR;
        $variables = <<<STR
        {
          "comment_id"	: $comment->id
        }
STR;
        $response = $this->json("POST", "/graphql", [
                'query'         => $query,  
                'variables'     => $variables,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    }
    /**
     * @Desc     添加新评论
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testAddCommentMutation(){
    	
    	$article = Article::inRandomOrder()->first();
    	
    	$comment_id = null;
    	$comments = $article->comments;
    	if( $comments->isNotEmpty() ){
    		$comment_id = $comments->random()->id;
    	}
    	$body = array_random(['纯文本的评论','@江湖小郎中 带了艾特人的评论']);
    	$visitor = User::inRandomOrder()->first();

        $query = <<<STR
		mutation addCommentMutation(\$commentable_id: Int!, \$body: String!,  \$comment_id: Int) {
		  addComment(commentable_id: \$commentable_id, body: \$body, comment_id: \$comment_id) {
		    id
		    body
		    user {
		      id
		      name
		      avatar
		    }
		    time_ago
		  }
		}
STR;
        
		if( is_null($comment_id) ){
    		$variables = <<<STR
	        {
	          "commentable_id"	: $article->id,
	          "body"   			: "$body"
	        }
STR;
    	} else {
			$variables = <<<STR
	        {
	          "commentable_id"	: $article->id,
	          "body"   			: "$body",
	          "comment_id"      : $comment_id
	        }
STR;
    	}

        $response = $this->actingAs($visitor)
        	->json("POST", "/graphql", [
                'query'         => $query,  
                'variables'     => $variables,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    }
    /**
     * @Desc     评论点赞
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testLikeCommentMutation(){
   		
   		$comment = Comment::inRandomOrder()->first();
    	$visitor = User::inRandomOrder()->first();

        $query = <<<STR
		mutation likeCommentMutation(\$comment_id: Int!) {
		  likeComment(comment_id: \$comment_id) {
		    id
		    likes
		  }
		}
STR;
		$variables = <<<STR
        {
          "comment_id"      : $comment->id
        }
STR;

        $response = $this->actingAs($visitor)
        	->json("POST", "/graphql", [
                'query'         => $query,  
                'variables'     => $variables,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    }
}