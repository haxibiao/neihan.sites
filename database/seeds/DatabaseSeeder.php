<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(AsoSeeder::class);
        // $this->call(SeoSeeder::class);
        // $this->call(TaskSeeder::class);
        $this->call(ContributeSeeder::class);

    }
}
