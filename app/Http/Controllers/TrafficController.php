<?php

namespace App\Http\Controllers;

use App\Traffic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TrafficController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($days_ago = 0)
    {
        $traffic_by_date = DB::table('traffic')->select(DB::raw('count(*) as count, date'))
            ->where('created_at','>=', Carbon::now()->subDay(7))            
            ->groupBy('date')
            ->pluck('count','date')
            ->toArray();

        $date = Carbon::now()->toDateString();
        if ($days_ago) {
            $date = Carbon::now()->subDay($days_ago)->toDateString();
        }
        $query  = Traffic::where('date', $date);
        $counts = [];
        $all    = $query->count();
        if ($all == 0) {
            $all = 1;
        }

        $counts['总计']    = $all;
        $counts['桌面']    = $query->where('is_desktop', 1)->count();
        $counts['移动端'] = $query->where('is_mobile', 1)->count();
        $counts['手机']    = $query->where('is_phone', 1)->count();
        $counts['平板']    = $query->where('is_tablet', 1)->count();
        $counts['微信']    = $query->where('is_wechat', 1)->count();
        $counts['安卓']    = $query->where('is_android_os', 1)->count();
        $counts['爬虫']    = $query->where('is_robot', 1)->count();

        $query          = DB::table('traffic')->where('date', $date);
        $data['device'] = [
            'cn_name' => '设备',
            'data'    => $query->select('device', DB::raw('count(*) as count'))
                ->groupby('device')
                ->pluck('count', 'device')];

        $data['platform'] = [
            'cn_name' => '系统',
            'data'    => $query->select('platform', DB::raw('count(*) as count'))
                ->groupby('platform')
                ->pluck('count', 'platform')];

        $data['browser'] = [
            'cn_name' => '浏览器',
            'data'    => $query->select('browser', DB::raw('count(*) as count'))
                ->groupby('browser')
                ->pluck('count', 'browser')];

        $data['robot'] = [
            'cn_name' => '爬虫',
            'data'    => $query->select('robot', DB::raw('count(*) as count'))
                ->groupby('robot')
                ->pluck('count', 'robot')];

        $data['referer_domain'] = [
            'cn_name' => '来源',
            'data'    => $query->select('referer_domain', DB::raw('count(*) as count'))
                ->groupby('referer_domain')
                ->pluck('count', 'referer_domain')];

        return view('traffic.index')
            ->withCounts($counts)
            ->withData($data)
            ->withTrafficByDate($traffic_by_date)
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

    public function robot($name)
    {
        $traffics = Traffic::where('robot', $name)->orderBy('id', 'desc')->paginate(20);
        return view('traffic.log')->withTraffics($traffics);
    }

    public function platform($name)
    {
        $traffics = Traffic::where('platform', $name)->orderBy('id', 'desc')->paginate(20);
        return view('traffic.log')->withTraffics($traffics);
    }

    public function browser($name)
    {
        $traffics = Traffic::where('browser', $name)->orderBy('id', 'desc')->paginate(20);
        return view('traffic.log')->withTraffics($traffics);
    }

    public function device($name)
    {
        $traffics = Traffic::where('device', $name)->orderBy('id', 'desc')->paginate(20);
        return view('traffic.log')->withTraffics($traffics);
    }

    public function referer_domain($name)
    {
        $traffics = Traffic::where('referer_domain', $name)->orderBy('id', 'desc')->paginate(20);
        return view('traffic.log')->withTraffics($traffics);
    }
}
