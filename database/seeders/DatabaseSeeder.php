<?php
namespace Database\Seeders;

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

        $this->call(TrafficSeeder::class);

        // $this->call(CleanupSeeder::class);

        // $this->call(SeoSeeder::class);
        // $this->call(AsoSeeder::class);

        // $this->call(AdConfigSeeder::class);
        // $this->call(TaskSeeder::class);
        // $this->call(VersionSeeder::class);

    }
}
