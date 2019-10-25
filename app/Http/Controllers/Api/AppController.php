<?php

namespace App\Http\Controllers\Api;

use App\AppConfig;
use App\Http\Controllers\Controller;
use App\Version;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index(Request $request)
    {
        $group   = request('name') ?? $request->header('name');
        $array   = [];
        $configs = AppConfig::whereGroup($group)->get();
        foreach ($configs as $config) {
            $array[$config->name] = $config->status;
        }
        $result = json_encode($array);
        return $result;
    }

    public function version(Request $request)
    {
        // $serverRoot = $request->root();

        $builder = Version::orderByDesc('id');
        $package = $request->input('package');
        if (!empty($package)) {
            $builder = $builder->where('package', $package);
        }

        if (is_prod_env()) {
            $builder = $builder->where('type', 1);
        }

        $versions   = [];
        $collection = $builder->get()->each(function ($version) use (&$versions) {
            array_push($versions, [
                'version'     => $version->name,
                'apk'         => $version->url,
                'is_force'    => (boolean) $version->is_force,
                'description' => $version->description,
                'size'        => formatBytes($version->size),
                'package'     => $version->package,
            ]);
        });

        return $versions;
    }
}
