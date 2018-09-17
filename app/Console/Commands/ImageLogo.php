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
    protected $signature = 'image:logo {--domain=}';

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
        if ($this->option('domain')) {
            $this->makeLogo($this->option('domain'));
        } else {
            $this->makeLogo(env('APP_DOMAIN'));
        }
    }

    public function makeLogo($domain)
    {
        $image = \ImageMaker::make(public_path('logo/' . $domain . '.png'));
        $image->resize(60, 60);
        $logoPath = public_path('logo/' . $domain . '.small.png');
        $image->save($logoPath);
        $this->info($logoPath);

        $image->resize(190, 190);
        $logoPath = public_path('logo/' . $domain . '.text.png');
        $image->save($logoPath);
        $this->info($logoPath);

        $image->resize(120, 120);
        $logoPath = public_path('logo/' . $domain . '.web.png');
        $image->save($logoPath);
        $this->info($logoPath);

        $image->resize(160, 160);
        $logoPath = public_path('logo/' . $domain . '.touch.png');
        $image->save($logoPath);
        $this->info($logoPath);
    }
}
