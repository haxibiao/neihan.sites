<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Query;

class SearchController extends Controller
{
    public function hotQueries(){
    	$queries=Query::where('status','>=',0)->orderBy('hits','desc')->paginate(10)->pluck('query');
    	$hot_queries=[];

    	foreach ($queries as $query) {
    		$q['full']=$query;
    		$q['short']=str_limit($query,14,'');
    		$hot_queries[]=$q;
    	}
    	return $hot_queries;
    }
}
