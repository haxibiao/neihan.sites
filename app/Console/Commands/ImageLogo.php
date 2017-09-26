<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImageLogo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:logo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make loge for all sizes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->makeLogo('dianmoge.com');
        $this->makeLogo('dongmeiwei.com');
        $this->makeLogo('dongdianyi.com');
        $this->makeLogo('dongdianyao.com');
    }

    public function makeLogo($domain)
    {
        $image = \ImageMaker::make(public_path('logo/' . $domain . '.jpg'));
        $image->resize(60, 60);
        $image->save(public_path('logo/' . $domain . '.small.jpg'));

        $image->resize(160, 160);
        $image->save(public_path('logo/' . $domain . '.touch.jpg'));

        $image->resize(120, 120);
        $image->save(public_path('logo/' . $domain . '.web.jpg'));
    }
}
