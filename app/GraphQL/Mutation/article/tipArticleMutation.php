<?php
namespace App\GraphQL\Mutation\article;

use App\Article;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class tipArticleMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'tipArticleMutation',
        'description' => '打赏文章',
    ];

    public function type()
    {
        return GraphQL::type('Article');
    }

    public function args()
    {
        return [
            'id'      => ['name' => 'id', 'type' => Type::int()],
            'amount'  => ['name' => 'amount', 'type' => Type::int()],
            'message' => ['name' => 'message', 'type' => Type::string()],
        ];
    }

    public function rules()
    {
        return [
            'id'     => ['required'],
            'amount' => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $user    = getUser();
        $article = Article::findOrFail($args['id']);
        $amount  = $args['amount'];

        //tip
        $message = isset($args['message']) ? $args['message'] : null;
        $tip = $article->tip($amount, $message);

        //balance changed
        $log_mine   = '向' . $article->user->link() . '的' . $article->link() . '打赏' . $amount . '元';
        $log_theirs = $user->link() . '向您的' . $article->link() . '打赏' . $amount . '元';
        $user->transfer($amount, $article->user, $log_mine, $log_theirs, $tip->id);

       

        return $article;
    }
}
