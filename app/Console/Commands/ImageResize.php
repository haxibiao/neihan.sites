<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImageResize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:resize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'resize existing big image files..';

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
        $images = \App\Image::all();
        foreach($images as $image) {
            $full_path = public_path($image->path);
            if(!file_exists($full_path)){
                continue;
            }
            $img = \ImageMaker::make($full_path);
            if($img->filesize() > 200*1024) {
                $width = $img->width() > 900 ? 900 : $img->width();
                $img->crop($width, 500);
                $img->save($full_path);

                $this->info($image->path);
            }
        }
    }
}
