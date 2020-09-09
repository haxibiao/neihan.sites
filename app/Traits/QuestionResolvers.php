<?php

namespace App\Traits;

use App\Question;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait QuestionResolvers
{
    public function resolveQuestions($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        
        $count = $args['count'] ?? 10;

        $user = auth('api')->user();

        $qb = Question::where('submit', Question::SUBMITTED_SUBMIT)
            ->latest('rank');
        
        if(!is_null($user)){
            $qb = $qb->where('user_id','!=',$user->id);
        }

        $questions = $qb->inRandomOrder()->take($count)->get();

        return $questions;
    }
}
