<?php

use App\Category;
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
        // foreach (Category::all() as $category) {
        //     $category->admins()->syncWithoutDetaching([
        //         $category->user->id => [
        //             'is_admin' => 1,
        //         ],
        //     ]);

        //     $category->authors()->syncWithoutDetaching([
        //         $category->user->id => [
        //             'approved' => 1,
        //         ],
        //     ]);
        // }

        DB::table('article_category')->update(['submit' => '已收录']);
    }
}
