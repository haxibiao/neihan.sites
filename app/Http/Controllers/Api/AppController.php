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
        //激励视频轮换，50%轮换为备用的激励视频 （注意nova需要配置备用的和默认的一样）
        if (rand(1, 10) > 5) {
            $adData['reward_video_prodiver'] = $adData['reward_video_prodiver2'];
            $adData['codeid_reward_video']   = $adData['codeid_reward_video2'];
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
