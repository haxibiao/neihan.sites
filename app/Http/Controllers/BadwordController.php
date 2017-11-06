<?php

namespace App\Http\Controllers;

use App\Badword;
use Auth;
use Illuminate\Http\Request;

class BadwordController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	//构造函数中验证是否登录，是否为编辑
	public function __construct() {
		$this->middleware('auth');
		//验证是否为编辑
		$this->middleware('auth.editor');
	}
	public function index() {
		$query = Badword::orderBy('id', 'desc');
		$badwords = $query->paginate(10);
		return view('badword.index')->withBadwords($badwords);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		return view('badword.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$id = Auth::user()->id;
		$badword = Badword::firstOrNew([
			'word' => $request->word,
		]);
		$badword->type = $request->type;
		$badword->user_id = $id;
		$badword->save();
		return redirect()->to('badword');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$badword = Badword::findOrFail($id);
		if ($badword) {
			$badword->delete();
			return redirect()->to('badword');
		}
	}
}
