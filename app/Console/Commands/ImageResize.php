<?php

namespace App\Console\Commands;

use App\Image;
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
        // $image = Image::find(1220);
        // $this->fix_image($image);
        // dd($image);
        // return;

        if (!is_dir(public_path('/storage/image/'))) {
            mkdir(public_path('/storage/image/'), 0777, 1);
        }

        $images = Image::orderBy('id')->get();
        foreach ($images as $image) {
            $this->fix_image($image);
        }
    }

    public function fix_image($image)
    {
        if (empty($image->path_origin)) {
            $image->path_origin = $image->path;
        }

        $this->info($image->path_origin);
        $path_bak      = str_replace('img/', 'img_bak/', $image->path_origin);
        $full_path     = public_path($path_bak);
        $extension     = pathinfo($image->path, PATHINFO_EXTENSION);
        $image->path   = '/storage/image/' . $image->id . '.' . $extension;
        $full_path_new = public_path($image->path);
        if (!file_exists($full_path) && !file_exists($full_path_new)) {
            $this->error($full_path . ' not exist ! also ' . $full_path_new);
            return;
        }
        if (file_exists($full_path)) {
            $this->info($full_path . ' copy to ' . $full_path_new);
            copy($full_path, $full_path_new);
        }

        $img = \ImageMaker::make($full_path_new);

        //fix top
        if ($extension != 'gif') {
            if ($img->width() >= 750) {
                $img->crop(750, 400);
                $image->path_top = '/storage/image/' . $image->id . '.top.' . $extension;
                $img->save(public_path($image->path_top));
            }
        } else {
            if ($img->width() >= 750) {
                $image->path_top = $image->path;
            }
        }

        //resize small
        $small_path = public_path($image->path_small); 
        if (file_exists($small_path)) {
            unlink($small_path);
        }
        $image->path_small = '/storage/image/' . $image->id . '.small.' . $extension;
        $small_path        = public_path($image->path_small);
        if ($img->width() / $img->height() < 1.5) {
            $img->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else {
            $img->resize(null, 200, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        $img->crop(300, 200);
        $img->save($small_path);
        $this->info($small_path);
        $image->save();
    }
}
