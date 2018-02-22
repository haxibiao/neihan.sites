<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Query;
use App\Article;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function hotQueries()
    {
        $queries     = Query::where('status', '>=', 0)->orderBy('hits', 'desc')->paginate(10)->pluck('query');
        $hot_queries = [];

        foreach ($queries as $query) {
            $q['full']     = $query;
            $q['short']    = str_limit($query, 14, '');
            $hot_queries[] = $q;
        }
        return $hot_queries;
    }

    public function serach(Request $request)
    {
        $type  = $request->type;
        $query = $request->get('query');
        if($type=="note"){
          return $this->serach_article($query);
        }

        if($type=='user'){
          return $this->serach_user($query);
        }

        if($type=='collection'){
            //code
        }

        if($type=='notebook'){
            //code
        }
    }

    public function serach_article($query)
    {
       $articles =Article::with('user')
       ->where('title','like','%'.$query.'%')
       ->orWhere('body','like','%'.$query.'%')
       ->orderBy('id','desc')
       ->paginate(5)
       ;

       foreach($articles as $article){
           $article->user->avatar=$article->user->getLatestAvatar();
           // $article->created_at= diffForHumansCN($article->created_at);
           $article->title       = str_replace($query, '<em>' . $query . '</em>', $article->title);
           $article->description = str_replace($query, '<em>' . $query . '</em>', $article->introduction());

           $users[]=$article->user;
       }

       $users=array_unique($users);
       if(count($users) > 3){
           $users =array_slice($users,0,3);
       }

       return $articles;
    }

    public function serach_user($query){

    }
}
