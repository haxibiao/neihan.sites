<?php

namespace App\Console\Commands;

use App\Image;
use Illuminate\Console\Command;

class ImageTitle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:title';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'refill image title from related articles';

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
        $images = Image::with('articles')->get();
        foreach ($images as $image) {
            if (!$image->articles->isEmpty()) {
                $article      = $image->articles->first();
                $image->title = $article->title;
                $image->save();

                $this->info($image->title);
            }

        }
    }
}
