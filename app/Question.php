<?php

namespace App;

use App\Exceptions\GQLException;
use  Haxibiao\Question\Question as BaseQuestion;

class Question extends BaseQuestion
{

    const MAX_ANSWER = 30;

    public function resolveCanAnswer($root, array $args, $context, $info)
    {
        if ($user = checkUser()) {
            if ($user->answer->where("created_at", ">=", now()->toDateString())->count() > Question::MAX_ANSWER) {
                throw new GQLException("今天答题超过上限啦~，明天再来吧 ->_<-");
            }
            return 1;
        }
    }
}
