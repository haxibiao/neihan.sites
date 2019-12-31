<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportVideo extends Command
{

    protected $signature = 'import:video {repo}';

    protected $description = '从其他库中导入视频';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if ($repo = $this->argument('repo')) {
            return $this->$repo();
        }
        return $this->error("必须提供你要导入数据的repo");
    }

    /**
     * 从答题赚钱中导入指定的分类中的视频
     * 详情见PM -> http://pm.haxibiao.com:8080/browse/DMG-131
     * @return void
     * @author zengdawei
     */
    public function datizhuanqian()
    {

    }
}
