<?php
namespace Database\Seeders;

use App\Movie;
use App\Stick;
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
        Stick::truncate();
        for ($i = 1; $i <= 5; $i++) {
            Stick::create([
                'name'      => '电影轮播图',
                'item_type' => '\App\Movie',
                'item_id'   => \App\Movie::query()->inRandomOrder()->first()->id,
            ]);

            Stick::create([
                'name'      => '首页短视频',
                'item_type' => '\App\Video',
                'item_id'   => \App\Video::whereNotNull('title')->inRandomOrder()->first()->id,
            ]);

            Stick::create([
                'name'      => '推荐美剧',
                'item_type' => '\App\Movie',
                'item_id'   => \App\Movie::where('category_id', Movie::MOVIE_MEI_JU)->inRandomOrder()->first()->id,
            ]);

            Stick::create([
                'name'      => '推荐韩剧',
                'item_type' => '\App\Movie',
                'item_id'   => \App\Movie::where('category_id', Movie::MOVIE_MEI_JU)->inRandomOrder()->first()->id,
            ]);

            Stick::create([
                'name'      => '推荐图文',
                'item_type' => '\App\Atricle',
                'item_id'   => \App\Article::publish()->inRandomOrder()->first()->id,
            ]);

            // Stick::create([
            //     'name'      => '推荐日剧',
            //     'item_type' => 'video',
            //     'item_id'   => \App\Movie::where('category_id', Movie::MOVIE_RI_JU)->inRandomOrder()->first()->id,
            // ]);

        }

    }
}
