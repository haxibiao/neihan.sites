<?php

use Illuminate\Http\Request;
use App\User;
use App\Article;
use App\Traffic;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//获取用户详细资料
Route::get('/user/{id}', function(Request $request, $id){
	$user = User::findOrFail($id);
	$data['user'] = $user;

	$data['articles_count'] = Article::where('user_id', $user->id)->count();
	$data['traffic_count'] = Traffic::where('user_id', $user->id)->count();

	$data['articles_count_yesterday'] = Article::where('user_id', $user->id)->where('date', Carbon::now()->subDay(1)->toDateString())->count();
	$data['traffic_count_yesterday'] = Traffic::where('user_id', $user->id)->where('date', Carbon::now()->subDay(1)->toDateString())->count();

	$data['articles_count_today'] = Article::where('user_id', $user->id)->where('date', Carbon::now()->toDateString())->count();
	$data['traffic_count_today'] = Traffic::where('user_id', $user->id)->where('date', Carbon::now()->toDateString())->count();

	return $data;
});

//get user id by user name
Route::get('/user/by-name/{name}', function(Request $request, $name){
	$user = User::where('name',$name)->first();
	return $user;
});
