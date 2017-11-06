<?php

use Illuminate\Database\Seeder;
use App\Badword;

class BadwordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Badword::create([
        	'word' => '骚'
        ]);
        Badword::create([
        	'word' => '他妈的'
        ]);
        Badword::create([
        	'word' => 'fuck'
        ]);
    }
}
