<?php

use Illuminate\Database\Seeder;

class AppConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        \App\AppConfig::firstOrCreate(
//            [
//                'group' => 'huawei',
//                'name'  => 'ad',
//                'state' => 1,
//            ]
//        );
//        \App\AppConfig::firstOrCreate(
//            [
//                'group' => 'huawei',
//                'name'  => 'wallet',
//                'state' => 1,
//            ]
//        );
//
//        \App\AppConfig::firstOrCreate(
//            [
//                'group' => 'android',
//                'name'  => 'ad',
//                'state' => 1,
//            ]
//        );
//        \App\AppConfig::firstOrCreate(
//            [
//                'group' => 'android',
//                'name'  => 'wallet',
//                'state' => 1,
//            ]
//        );
//
//        \App\AppConfig::firstOrCreate(
//            [
//                'group' => 'ios',
//                'name'  => 'ad',
//                'state' => 1,
//            ]
//        );
//        \App\AppConfig::firstOrCreate(
//            [
//                'group' => 'ios',
//                'name'  => 'wallet',
//                'state' => 1,
//            ]
//        );
        \App\AppConfig::firstOrCreate(
            [
                'group' => 'record',
                'name'  => 'web',
                'state' => 0,
            ]
        );
    }
}
