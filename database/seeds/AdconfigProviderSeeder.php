<?php

use App\AdConfig;
use Illuminate\Database\Seeder;

class AdconfigProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //provider
        $config        = AdConfig::where('name', 'splash_provider')->first();
        $config->value = '腾讯';
        $config->save();
    }
}
