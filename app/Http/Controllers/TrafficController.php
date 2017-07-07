<?php

namespace App\Http\Controllers;

use App\Traffic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrafficController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $counts = [];
        $all    = Traffic::count();
        if ($all == 0) {
            $all = 1;
        }

        $counts['总计']    = $all;
        $counts['桌面']    = Traffic::where('is_desktop', 1)->count();
        $counts['移动端'] = Traffic::where('is_mobile', 1)->count();
        $counts['手机']    = Traffic::where('is_phone', 1)->count();
        $counts['平板']    = Traffic::where('is_tablet', 1)->count();
        $counts['微信']    = Traffic::where('is_wechat', 1)->count();
        $counts['安卓']    = Traffic::where('is_android_os', 1)->count();
        $counts['爬虫']    = Traffic::where('is_robot', 1)->count();

        $data['设备']    = DB::table('traffic')->select('device', DB::raw('count(*) as count'))->groupby('device')->pluck('count', 'device');
        $data['系统']    = DB::table('traffic')->select('platform', DB::raw('count(*) as count'))->groupby('platform')->pluck('count', 'platform');
        $data['浏览器'] = DB::table('traffic')->select('browser', DB::raw('count(*) as count'))->groupby('browser')->pluck('count', 'browser');
        $data['爬虫']    = DB::table('traffic')->select('robot', DB::raw('count(*) as count'))->groupby('robot')->pluck('count', 'robot');
        
        return view('traffic.index')
            ->withCounts($counts)
            ->withData($data)
            ->withAll($all);
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

    public function log()
    {
        $traffics = Traffic::orderBy('id', 'desc')->paginate(20);
        return view('traffic.log')->withTraffics($traffics);
    }

    public function robot()
    {

    }
}
