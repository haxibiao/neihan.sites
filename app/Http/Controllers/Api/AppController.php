<?php

namespace App\Http\Controllers\Api;

use App\AdConfig;
use App\AppConfig;
use App\Http\Controllers\Controller;
use App\Version;
use Illuminate\Http\Request;

class AppController extends Controller
{
    //返回 app-config (含ad config)
    public function index(Request $request)
    {
        //方便 &name=android 这样调试, 兼容旧版本传name..
        $group = request('name') ?? $request->header('name');
        $store = request('store') ?? $request->header('store');

        if (empty($group)) {
            $group = request('os') ?? $request->header('os');
        }

        //华为单独有开关
        if ($store == "huawei") {
            $group = 'huawei';
        }

        $array   = [];
        $configs = AppConfig::whereGroup($group)->get();
        foreach ($configs as $config) {
            $array[$config->name] = $config->status;
        }

        //AdConfig的合并一起返回
        $array = array_merge($array, $this->getAdConfig());
        return $array;
    }

    public function getAdConfig()
    {
        $adData = [];
        foreach (AdConfig::all() as $adconfig) {
            $adData[$adconfig->name] = $adconfig->value;
        }

        //开屏混合 ----------------------------
        if ($adData['splash_provider'] == '混合') {
            if (rand(1, 100) % 3 == 0) {
                $adData['splash_provider'] = '腾讯';
            } else if (rand(1, 100) % 3 == 1) {
                $adData['splash_provider'] = '百度';
            } else {
                $adData['splash_provider'] = '头条';
            }
        }

        //信息流混合 ----------------------------
        if ($adData['feed_provider'] == '混合') {
            if (rand(1, 100) % 3 == 0) {
                $adData['feed_provider'] = '腾讯';
            } else if (rand(1, 100) % 3 == 1) {
                $adData['feed_provider'] = '百度';
            } else {
                $adData['feed_provider'] = '头条';
            }
        }

        //激励视频混合 ----------------------------
        if ($adData['reward_video_provider'] == '混合') {
            if ($user = getUser(false)) {
                //统计激励次数，并强制每次轮换平台
                $counter = $user->rewardCounter;
                if ($counter->last_provider == "头条") {
                    $adData['reward_video_provider'] = '腾讯';
                    $counter->count_tencent          = $counter->count_tencent + 1;
                    $counter->last_provider          = "腾讯";
                } else {
                    $adData['reward_video_provider'] = '头条';
                    $counter->count_toutiao          = $counter->count_toutiao + 1;
                    $counter->last_provider          = "头条";
                }
                $counter->count = $counter->count + 1;
                $counter->save();
            } else {
                //没用户信息时，简单随机
                if (rand(1, 100) % 2 == 0) {
                    $adData['reward_video_provider'] = '腾讯';
                } else {
                    $adData['reward_video_provider'] = '头条';
                }
            }
        }

        return $adData;
    }

    //api/ad-config 返回广告的configs
    public function adConfig(Request $request)
    {
        return $this->getAdConfig();
    }

    public function version(Request $request)
    {

        $builder = Version::where('os', 'Android')->orderByDesc('id');
        $package = $request->input('package');
        if (!empty($package)) {
            $builder = $builder->where('package', $package);
        }

        if (is_prod_env()) {
            $builder = $builder->where('type', 1);
        }

        $version = $builder->get();

        $array = $version->toArray();
        return array_map(static function ($item) {
            return array(
                'version'     => $item['name'],
                'apk'         => $item['url'],
                'is_force'    => $item['is_force'],
                'description' => $item['description'],
                'size'        => formatSizeUnits($item['size']),
                'package'     => $item['package'],
            );
        }, $array);
    }
}
