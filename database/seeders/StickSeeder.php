<?php
namespace Database\Seeders;

use App\Movie;
use App\Stickable;
use App\Video;
use Illuminate\Database\Seeder;

class StickSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Stickable::truncate();

        for ($i = 1; $i <= 5; $i++) {
            Stickable::create([
                'name'           => '电影轮播图',
                'stickable_type' => '\App\Movie',
                'stickable_id'   => \App\Movie::query()->inRandomOrder()->first()->id,
            ]);

            Stickable::create([
                'name'           => '首页短视频',
                'stickable_type' => '\App\Video',
                'stickable_id'   => \App\Video::whereNotNull('title')->inRandomOrder()->first()->id,
            ]);

            Stickable::create([
                'name'           => '推荐美剧',
                'stickable_type' => '\App\Movie',
                'stickable_id'   => \App\Movie::where('category_id', Movie::MOVIE_MEI_JU)->inRandomOrder()->first()->id,
            ]);

            Stickable::create([
                'name'           => '推荐韩剧',
                'stickable_type' => '\App\Movie',
                'stickable_id'   => \App\Movie::where('category_id', Movie::MOVIE_MEI_JU)->inRandomOrder()->first()->id,
            ]);

            Stickable::create([
                'name'           => '推荐图文',
                'stickable_type' => '\App\Atricle',
                'stickable_id'   => \App\Article::publish()->inRandomOrder()->first()->id,
            ]);

            // Stickable::create([
            //     'name'      => '推荐日剧',
            //     'stickable_type' => 'video',
            //     'stickable_id'   => \App\Movie::where('category_id', Movie::MOVIE_RI_JU)->inRandomOrder()->first()->id,
            // ]);

        }

    }
}
