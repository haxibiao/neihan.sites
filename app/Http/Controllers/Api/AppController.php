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

        //TODO: 华为单独有开关
        if ($store == "huawei") {
            $group = 'huawei';
        }

        $array   = [];
        $configs = AppConfig::whereGroup($group)->get();

        foreach ($configs as $config) {
            $array[$config->name] = $config->status;
        }

        //吧AdConfig的合并一起返回
        //TODO: 以后可以实现不同用户，返回不同的ad config
        $adData = [];
        foreach (AdConfig::all() as $adconfig) {
            $adData[$adconfig->name] = $adconfig->value;
        }
        $array = array_merge($array, $adData);

        return $array;
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
