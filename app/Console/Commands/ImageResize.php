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
        // $image = Image::find(21);
        // dd($image);
        // $this->fix_image($image);
        // return;

        $images = Image::all();
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

        $full_path     = public_path($image->path_origin);
        $extension     = pathinfo($image->path, PATHINFO_EXTENSION);
        $image->path   = '/img/' . $image->id . '.' . $extension;
        $full_path_new = public_path($image->path);
        if (!file_exists($full_path) && !file_exists($full_path_new)) {
            $this->error($full_path . ' not exist ! also ' . $full_path_new);
            return;
        }
        if (file_exists($full_path)) {
            rename($full_path, $full_path_new);
        }

        $img = \ImageMaker::make($full_path_new);

        // //fix big
        // if ($img->filesize() > 200 * 1024) {
        //     $width = $img->width() > 750 ? 750 : $img->width();
        //     $img->crop($width, 400);
        //     $img->save($full_path_new);

        //     $this->info($image->path);
        // }

        //resize small
        $small_path = public_path($image->path_small); //$full_path_new . '.small.jpg';
        if (file_exists($small_path)) {
            unlink($small_path);
        }
        $image->path_small = '/img/' . $image->id . '.small.' . $extension;
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
