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

        // $this->call(AdConfigSeeder::class);
        $this->call(TaskSeeder::class);
        // $this->call(FunctionSwitchSeeder::class);
        // $this->call(AsoSeeder::class);

        // $this->call(AdConfigSeeder::class);
        //$this->call(VersionSeeder::class);

    }
}
