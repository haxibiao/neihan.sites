<?php

namespace App\Http\GraphQL\Queries;

use App\Article;
use App\Scopes\ArticleSubmitScope;

class ArticleQueries
{
    public function articles($root, array $args, $context)
    {

        //TODO 重构并迁移到 ArticleRepo 中
        //排除用户拉黑（屏蔽）的用户发布的视频
        $userBlockId = [];
        if ($user = checkUser()) {
            $userBlockId = \App\UserBlock::select('user_block_id')->where('user_id', $user->id)->get();
        }

        $query = Article::withoutGlobalScope(ArticleSubmitScope::class)
            ->whereIn('type', ['video', 'post', 'issue'])
            ->orderBy('id', 'desc');

        if ($userBlockId) {
            $query->whereNotIn('user_id', $userBlockId);
        }

        if ($args['submit'] != 10) {
            $query->where('submit', $args['submit'])->whereNotNull('cover_path');
        }

        if ($args['status'] != 10) {
            return $query->where('status', $args['status'])->whereNotNull('cover_path');
        }
        $query->when(isset($args['user_id']), function ($q) use ($args) {
            return $q->where('user_id', $args['user_id']);
        });
        $query->when(isset($args['category_id']), function ($q) use ($args) {
            return $q->where('category_id', $args['category_id']);
        });
        return $query;
    }
}
