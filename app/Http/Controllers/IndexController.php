<?php

namespace App\Http\Controllers;
use App\Article;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index() {
    	$zhongyi_articles = Article::orderBy('id', 'desc')->take(6)->get();
    	$xiyi_articles = Article::orderBy('id', 'desc')->take(6)->get();
    	return view('index.index')
    		->withZhongyiArticles($zhongyi_articles)
    		->withXiyiArticles($xiyi_articles);
    }

    public function zhongyi() {

    	return view('index.zhongyi');
    }

    public function xiyi() {

    	return view('index.xiyi');
    }
}
