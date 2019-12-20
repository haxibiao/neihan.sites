<?php

    namespace App\Http\Controllers\Api;

    use App\AppConfig;
    use App\Http\Controllers\Controller;
    use App\Version;
    use Illuminate\Http\Request;
    use Illuminate\Support\Arr;

    class AppController extends Controller
    {
        public function index(Request $request)
        {
            $group = request('name') ?? $request->header('name');
            $array = [];
            $configs = AppConfig::whereGroup($group)->get();

            foreach ($configs as $config) {
                $array[$config->name] = $config->status;
            }

            $data = AppConfig::whereGroup('AD')->first();

            if ($data !== null && $data->data !== null){
                $array = array_merge($array,$data->data);
            }

            $result = json_encode($array);
            return $result;
        }

        public function version(Request $request)
        {

            $builder = Version::where('os','Android')->orderByDesc('id');
            $package = $request->input('package');
            if (!empty($package)) {
                $builder = $builder->where('package', $package);
            }

            if (is_prod_env()) {
                $builder = $builder->where('type', 1);
            }

            $version = $builder->get();

            $array = $version->toArray();
            return array_map(static function($item){
                return array (
                    'version' => $item['name'],
                    'apk' => $item['url'],
                    'is_force' => $item['is_force'],
                    'description' => $item['description'],
                    'size' => formatSizeUnits($item['size']),
                    'package' => $item['package'],
                );
            },$array);
        }
    }
