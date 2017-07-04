<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cate = Category::firstOrNew([
        		'name' => '中医',
        		'name_en' => 'zhongyi'
        	]);
        $cate->user_id = 1;
        $cate->level = 0;
        $cate->save();
        $cate = Category::firstOrNew([
        		'name' => '西医',
        		'name_en' => 'xiyi'
        	]);
        $cate->user_id = 1;
        $cate->level = 0;
        $cate->save();
        $cate = Category::firstOrNew([
                'name' => '兽医',
                'name_en' => 'shouyi'
            ]);
        $cate->user_id = 1;
        $cate->level = 0;
        $cate->save();
    }
}
