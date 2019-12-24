<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('categories')
            ->where('id', '>', 74) //新版本建立的都为视频化时代
            ->update([
                'type'   => 'video',
                'status' => 1,
            ]);

        // foreach (Category::all() as $category) {
        //     $category->admins()->syncWithoutDetaching([
        //         $category->user->id=>[
        //             'is_admin'=>1,
        //         ],
        //     ]);
        //     $category->authors()->syncWithoutDetaching([
        //         $category->user->id=>[
        //             'approved'=>1,
        //         ],
        //     ]);
        // }

    }
}
