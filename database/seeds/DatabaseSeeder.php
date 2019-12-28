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
        $this->call(AppConfigSeeder::class);
//        $this->call(AdConfigSeeder::class);
        // $this->call(TaskSeeder::class);
        // $this->call(FunctionSwitchSeeder::class);
    }
}
