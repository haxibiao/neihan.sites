<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return   void
     */
    public function run()
    {
        //develop上测试开关和adconfig
        // $this->call(AppConfigSeeder::class);
        $this->call(AdConfigSeeder::class);
        // $this->call(FunctionSwitchSeeder::class);
    }
}
