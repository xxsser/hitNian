<?php

use Illuminate\Database\Seeder;

class PrizeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Prize::create([
            'rank'      =>  1,
            'name'      =>  'iphone 6s',
            'num'       =>  1,
            'coin'      =>  999999999,
            'type'      =>  'rank',
        ]);
        \App\Prize::create([
            'rank'      =>  2,
            'name'      =>  '电烤箱',
            'num'       =>  3,
            'coin'      =>  999999999,
            'type'      =>  'rank',
        ]);
        \App\Prize::create([
            'rank'      =>  3,
            'name'      =>  '小米手环',
            'num'       =>  5000,
            'coin'      =>  999999999,
            'type'      =>  'rank',
        ]);
        \App\Prize::create([
            'rank'      =>  4,
            'name'      =>  '10元代金券',
            'num'       =>  500,
            'coin'      =>  4500,
            'type'      =>  'money',
        ]);
        \App\Prize::create([
            'rank'      =>  5,
            'name'      =>  '8元代金券',
            'num'       =>  1000,
            'coin'      =>  4000,
            'type'      =>  'money',
        ]);

        \App\Prize::create([
            'rank'      =>  6,
            'name'      =>  '5元代金券',
            'num'       =>  2000,
            'coin'      =>  3000,
            'type'      =>  'money',
        ]);
        \App\Prize::create([
            'rank'      =>  7,
            'name'      =>  '2元代金券',
            'num'       =>  3000,
            'coin'      =>  1800,
            'type'      =>  'money',
        ]);
        \App\Prize::create([
            'rank'      =>  8,
            'name'      =>  '1元代金券',
            'num'       =>  5000,
            'coin'      =>  1000,
            'type'      =>  'money',
        ]);
        \App\Prize::create([
            'rank'      =>  9,
            'name'      =>  '洗手液',
            'num'       =>  80,
            'coin'      =>  5000,
            'type'      =>  'gift',
        ]);
    }
}
