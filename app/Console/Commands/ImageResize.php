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
        foreach ($images as $image) {
            $full_path = public_path($image->path);
            if (!file_exists($full_path)) {
                continue;
            }
            $img = \ImageMaker::make($full_path);

            // //fix big
            // if ($img->filesize() > 200 * 1024) {
            //     $width = $img->width() > 750 ? 750 : $img->width();
            //     $img->crop($width, 400);
            //     $img->save($full_path);

            //     $this->info($image->path);
            // }

            //resize small
            $small_path = public_path($image->path_small); //$full_path . '.small.jpg';
            if ($img->height() > $img->width()) {
                $img->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // if ($img->width > 200) {
                //     $img->crop(300, 200);
                // }

            } else {
                $img->resize(null, 200, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // if ($img->height() > 300) {
                //     $img->crop(300, 200);
                // }
            }
            $img->crop(300, 200);
            $img->save($small_path);
            $this->info($small_path);
        }
    }
}
