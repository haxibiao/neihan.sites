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

        $this->call(CleanupSeeder::class);

        // $this->call(AdConfigSeeder::class);
        // $this->call(TaskSeeder::class);
        // $this->call(AsoSeeder::class);
        // $this->call(AdconfigProviderSeeder::class);
        // $this->call(VersionSeeder::class);

    }
}
