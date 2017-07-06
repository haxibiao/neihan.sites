<?php

namespace App\Http\Controllers;

use App\Traffic;
use Illuminate\Http\Request;

class TrafficController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $all = Traffic::count();
        $data['总计'] = $all;
        $data['桌面'] = Traffic::where('is_desktop',1)->count();
        $data['移动端'] = Traffic::where('is_mobile',1)->count();
        $data['手机'] = Traffic::where('is_phone',1)->count();
        $data['平板'] = Traffic::where('is_tablet',1)->count();
        $data['微信'] = Traffic::where('is_wechat',1)->count();
        $data['安卓'] = Traffic::where('is_android_os',1)->count();
        $data['爬虫'] = Traffic::where('is_robot',1)->count();


        $traffics = Traffic::orderBy('id', 'desc')->paginate(100);
        return view('traffic.index')
            ->withData($data)
            ->withAll($all)
            ->withTraffics($traffics);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
