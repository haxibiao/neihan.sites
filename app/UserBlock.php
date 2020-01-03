<?php

namespace App;

use App\User;
use App\Article;
use App\Exceptions\GQLException;
use App\Traits\UserBlockResolvers;
use Illuminate\Database\Eloquent\Model;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UserBlock extends Model
{
    use UserBlockResolvers;
    
    public $fillable=[
            "user_id",
            "user_block_id",
    ];

    public function user(){
        return $this->belongsTo(\App\User::class);
    }

    public function userBlock(){
        return $this->belongsTo(\App\User::class,'user_block_id','id');
    }

    public function articleBlock(){
        return $this->belongsTo(\App\Article::class,'article_block_id','id');
    }

    public function articleReport(){
        return $this->belongsTo(\App\Article::class,'article_report_id','id');
    }
}
